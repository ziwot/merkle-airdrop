#import "ligo-breathalyzer/lib/lib.mligo" "Breath"
#import "./merkle_proof.mligo" "MerkleProof_suite"
#import "./test_airdrop.mligo" "Airdrop_suite"

let () =
  Breath.Model.run_suites Trace [MerkleProof_suite.suite; Airdrop_suite.suite]
