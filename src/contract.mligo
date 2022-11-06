#import "./constants.mligo" "Constants"
#import "./merkle_proof.mligo" "MerkleProof"
#import "./errors.mligo" "Errors"
#import "./storage.mligo" "Storage"

type claim_params =
  [@layout:comb]
  {addr : address;
   amnt : nat;
   merkle_proof : bytes list}

type parameter = Claim of claim_params

type storage = Storage.t

type result = operation list * storage

let claim (s : storage) (p : claim_params) =
  let {addr = addr;
       amnt = amnt;
       merkle_proof = merkle_proof} =
    p in
  let () = Storage.assert_not_claimed s addr in
  let leaf = MerkleProof.get_leaf (Bytes.pack (addr, amnt)) in
  let () =
    assert_with_error
      (not (MerkleProof.verify
          merkle_proof
          s.merkle_root
          leaf))
      Errors.invalid_proof in
  let claimed = Big_map.add addr unit s.claimed in
  Constants.no_operation, {s with claimed}

let main (action, store : parameter * storage) =
  match action with
  Claim p -> claim store p

let generate_initial_storage
  (admin, about, merkle_root, claimed :
   address * bytes * bytes * Storage.claimed) : storage =
  let metadata = (Big_map.empty : Storage.Metadata.t) in
  let metadata : Storage.Metadata.t =
    Big_map.update
      ("")
      (Some (Bytes.pack ("tezos-storage:content")))
      metadata in
  let metadata =
    Big_map.update ("content") (Some (about)) metadata in
  {metadata = metadata;
   admin = admin;
   merkle_root = merkle_root;
   claimed = claimed}

