(* Error codes used throughout the airdrop contract *)
module Errors = struct
  let already_claimed = "ALREADY_CLAIMED"

  let invalid_proof = "INVALID_PROOF"

  let fa_not_found = "FA_NOT_FOUND"
  end

(* TZIP-16 metadata storage: map from string keys to bytes values *)
(* Reference: https://tzip.tezosagora.org/proposal/tzip-16/ *)
module Metadata = struct
  type t = (string, bytes) big_map
  end

(* FA2 token interface for transferring airdrop tokens *)
module Token = struct
  type token_id = nat

  type t = address * token_id

  (* Resolves the %transfer entrypoint of an FA2 token contract *)
  let get_transfer_entrypoint (addr : address) =
    match (Tezos.get_entrypoint_opt "%transfer" addr) with
      None -> failwith Errors.fa_not_found
    | Some c -> c

  (* Constructs an FA2 transfer request to send tokens to a recipient *)
  (* The from_ address is set to the airdrop contract itself (self) *)
  let transfer ((token_addr, token_id), to_, amount : t * address * nat)
  : operation =
    let dest = get_transfer_entrypoint (token_addr) in
    let transfer_requests =
      ([
         ({
           from_ = Tezos.get_self_address ();
           txs =
             ([
                {
                 to_;
                 token_id;
                 amount
                }
              ])
          })
       ]) in
    Tezos.Next.Operation.transaction transfer_requests 0mutez dest
  end

(* Contract configuration stored on-chain *)
module Config = struct
  type t =
    {
     (* FA2 token address and token_id for the airdrop token *)
     token
     : Token.t;
     (* Merkle root hash for validating claims *)
     merkle_root
     : bytes
    }
  end

(* Merkle proof verification for claim validation *)
module MerkleProof = struct
  (* Verifies a merkle proof against a root hash.
     Folds through each proof element, concatenating with current hash
     and computing sha256 at each step. Final hash must equal root. *)
  let verify (proof, root, leaf : bytes list * bytes * bytes) =
    (List.fold
       (fun (acc, h : bytes * bytes) -> Crypto.sha256 (Bytes.concat h acc))
       proof
       leaf)
    = root

  (* Computes the leaf hash for a claim: sha256(pack(address, amount)) *)
  let get_leaf (message : bytes) : bytes = Crypto.sha256 message
  end

(* Entrypoint parameter for claiming airdrop tokens *)
type parameter =
  [@layout comb]
  {
   (* Address receiving the airdrop tokens *)
   addr
   : address;
   (* Amount being claimed *)
   amnt
   : nat;
   (* Merkle proof path from leaf to root *)
   merkle_proof
   : bytes list
  }

(* Contract storage: tracks metadata, config, and claimed addresses *)
module Storage = struct
  (* Tracks which addresses have already claimed their airdrop *)
  type claimed = (address, unit) big_map

  type t =
    {
     (* TZIP-16 metadata for contract *)
     metadata
     : Metadata.t;
     (* Token and merkle root configuration *)
     config
     : Config.t;
     (* Map of addresses that have claimed *)
     claimed
     : claimed
    }

  (* Ensures an address has not already claimed *)
  let assert_not_claimed (s : t) (addr : address) =
    match (Big_map.mem addr s.claimed) with
      false -> ()
    | true -> failwith Errors.already_claimed

  (* Registers a claim by adding the address to the claimed map *)
  let register_claim (s : t) (addr : address) =
    let claimed = Big_map.add addr unit s.claimed in
    {s with claimed}
  end

type storage = Storage.t

type result = operation list * storage

(* Main entrypoint: validates proof, transfers tokens, registers claim *)

[@entry]
let claim
  ({
    addr;
    amnt;
    merkle_proof
   }
   : parameter)
  (s : storage) =
  (* Check address has not already claimed *)
  let () = Storage.assert_not_claimed s addr in
  (* Compute leaf hash from packed (address, amount) *)
  let leaf = MerkleProof.get_leaf (Bytes.pack (addr, amnt)) in
  (* Verify merkle proof is valid *)
  let () =
    Assert.Error.assert
      (not (MerkleProof.verify (merkle_proof, s.config.merkle_root, leaf)))
      Errors.invalid_proof in
  (* Transfer tokens to claimer and mark address as claimed *)
    [
    Token.transfer (s.config.token, addr, amnt)
  ],
  Storage.register_claim s addr

(* offchain view *)
let get_claim_status (addr : address) (s : storage) = Big_map.mem addr s.claimed

(* Initializes contract storage with TZIP-16 metadata *)
let generate_initial_storage
  (about, token, merkle_root, claimed
   : bytes * Token.t * bytes * Storage.claimed) =
  let metadata : Metadata.t =
    Big_map.literal
      [("", [%bytes "tezos-storage:contents"]); ("contents", about)] in
  let config =
    {
     token;
     merkle_root
    } in
  {
   metadata;
   config;
   claimed
  }
