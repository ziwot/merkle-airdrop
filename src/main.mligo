#import "./constants.mligo" "Constants"
#import "./merkle_proof.mligo" "MerkleProof"
#import "./storage.mligo" "Storage"

type claim_params =
  [@layout:comb]
  {addr : address;
   amnt : nat;
   merkle_proof : bytes}

type parameter = Claim of claim_params

type storage = Storage.t

type result = operation list * storage

let claim (s : storage) (p : claim_params) =
  Constants.no_operation, s

let main (action, store : parameter * storage) =
  match action with
  Claim p -> claim store p
