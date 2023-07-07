#import "@ligo/fa/lib/fa2/asset/multi_asset.mligo" "FA"
module Errors = struct
  let already_claimed = "ALREADY_CLAIMED"

  let invalid_proof = "INVALID_PROOF"

  let fa_not_found = "FA_NOT_FOUND"

  let not_admin = "NOT_ADMIN"

end

module Metadata = struct
  (* tzip-16 https://tzip.tezosagora.org/proposal/tzip-16/ *)

  type t = (string, bytes) big_map

end

module Token = struct
  type token_id = nat

  type t = address * token_id

  let get_transfer_entrypoint (addr : address) : FA.transfer contract =
    match (Tezos.get_entrypoint_opt "%transfer" addr
       : FA.transfer contract option)
    with
      None -> failwith Errors.fa_not_found
    | Some c -> c

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
              ]
              : FA.atomic_trans list)
          })
       ]
       : FA.transfer) in
    Tezos.transaction transfer_requests 0mutez dest

end

module Config = struct
  type t =
    {
     token : Token.t;
     merkle_root : bytes
    }

end

module MerkleProof = struct
  let verify (proof, root, leaf : bytes list * bytes * bytes) =
    (List.fold
       (fun (acc, h : bytes * bytes) -> Crypto.sha256 (Bytes.concat h acc))
       proof
       leaf)
    = root

  let get_leaf (message : bytes) : bytes = Crypto.sha256 message

end

type parameter =
  [@layout comb]
  {
   addr : address;
   amnt : nat;
   merkle_proof : bytes list
  }

module Storage = struct
  type claimed = (address, unit) big_map

  type t =
    {
     metadata : Metadata.t;
     config : Config.t;
     claimed : claimed
    }

  let assert_not_claimed (s : t) (addr : address) =
    match (Big_map.find_opt addr s.claimed) with
      None -> ()
    | Some -> failwith Errors.already_claimed

  let register_claim (s : t) (addr : address) =
    let claimed = Big_map.add addr unit s.claimed in
    {s with claimed}

end

type storage = Storage.t

type result = operation list * storage

let claim
  (s : storage)
  ({
    addr;
    amnt;
    merkle_proof
   }
   : parameter) =
  let () = Storage.assert_not_claimed s addr in
  let leaf = MerkleProof.get_leaf (Bytes.pack (addr, amnt)) in
  let () =
    assert_with_error
      (not (MerkleProof.verify (merkle_proof, s.config.merkle_root, leaf)))
      Errors.invalid_proof in
  [Token.transfer (s.config.token, addr, amnt)], Storage.register_claim s addr

let main (p, store : parameter * storage) = claim store p

let generate_initial_storage
  (about, token, merkle_root, claimed
   : bytes * Token.t * bytes * Storage.claimed) =
  let metadata = (Big_map.empty : Metadata.t) in
  let metadata : Metadata.t =
    Big_map.update ("") (Some (Bytes.pack ("tezos-storage:content"))) metadata in
  let metadata = Big_map.update ("content") (Some (about)) metadata in
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
