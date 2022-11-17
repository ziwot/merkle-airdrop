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
