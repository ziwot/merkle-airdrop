#import "./merkle_proof.mligo" "MerkleProof"
#import "./errors.mligo" "Errors"
#import "./storage.mligo" "Storage"
#import "./token.mligo" "Token"

type parameter =
  [@layout:comb]
  {addr : address;
   amnt : nat;
   merkle_proof : bytes list}

type storage = Storage.t

type result = operation list * storage

let claim (s : storage) ({addr; amnt; merkle_proof} : parameter) =
  let () = Storage.assert_not_claimed s addr in
  let leaf = MerkleProof.get_leaf (Bytes.pack (addr, amnt)) in
  let () =
    assert_with_error
      (not (MerkleProof.verify
          (merkle_proof, s.config.merkle_root, leaf)))
      Errors.invalid_proof in
  [Token.transfer (s.config.token, addr, amnt)],
  Storage.register_claim s addr

let main (p, store : parameter * storage) = claim store p

let generate_initial_storage (about, token, merkle_root, claimed :
   bytes * Token.t * bytes * Storage.claimed) =
  let metadata = (Big_map.empty : Storage.Metadata.t) in
  let metadata : Storage.Metadata.t =
    Big_map.update
      ("")
      (Some (Bytes.pack ("tezos-storage:content")))
      metadata in
  let metadata =
    Big_map.update ("content") (Some (about)) metadata in
  let config = { token; merkle_root } in
  {metadata; config; claimed}
