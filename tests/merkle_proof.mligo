#import "ligo-breathalyzer/lib/lib.mligo" "B"
#import "../src/merkle_proof.mligo" "MerkleProof"

let case_happy_path =
  B.Model.case
    "verify"
    "verify with valid args should return true"
    (fun (_: B.Logger.level) ->
       let expected = True in
       let computed = MerkleProof.verify [
          0x0ffa00dc1b8a89b698e140416c8d162bd9c599e6f5a7e3ef9ffb30a4c6f1d4b1;
          0x61c41e2300aedaec6ef4e4b50462e2da1fa2177f22530df79068d29b44a79b89
        ] 0x4ea4cd9389fa1c4cfd8051d32bd3ee7c898690139a94c32d566f6d55b0ad4447
        0x73e6a3e9e5a2b3d909f55b698cea5a668307a34b4fd5029d9a010f7183429806
       in
       B.Assert.is_true "should be equal" (expected = computed))

let case_nohappy_path =
  B.Model.case
    "verify"
    "verify with invalid args should return false"
    (fun (_: B.Logger.level) ->
       let expected = False in
       let computed = MerkleProof.verify [
          0x4ea4cd9389fa1c4cfd8051d32bd3ee7c898690139a94c32d566f6d55b0ad4447;
          0x61c41e2300aedaec6ef4e4b50462e2da1fa2177f22530df79068d29b44a79b89
        ] 0x0ffa00dc1b8a89b698e140416c8d162bd9c599e6f5a7e3ef9ffb30a4c6f1d4b1
        0x73e6a3e9e5a2b3d909f55b698cea5a668307a34b4fd5029d9a010f7183429806
       in
       B.Assert.is_true "should be equal" (expected = computed))

let suite =
  B.Model.suite
    "Test suite for the MerkleProof Module"
    [ case_happy_path; case_nohappy_path ]
