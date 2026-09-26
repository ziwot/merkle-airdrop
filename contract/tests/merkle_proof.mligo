#import "ligo-breathalyzer/lib/lib.mligo" "B"
#import "../src/airdrop.mligo" "A"

(* Fixture tree, over the four drops of docs/10-Sandbox-Sample-Data.md, see
   test_airdrop.mligo for the details. *)
let root = 0xce2ca1226de039dd82f7dee62d8d17896bc9dc352b6ca705b0c7ac2431407d0d

let leaf = 0xad868caa9524dc6547538235f73eaede6abd8a12b5bfaf836d34629b49f80a68

let proof =
  [
    (0x11d493116efc9abebcdb30c7df8d41f0f2d80bb73348eb7dd4a2ae99c7a62b2c, false);
    (0x23b9ad2635bab6a413a847cee949c20abd17bff89576a9020d553dde2401d3f3, true)
  ]

let case_happy_path =
  B.Model.case
    "verify"
    "verify with valid args should return true"
    (fun (_ : B.Logger.level) -> let expected = True in
       let computed = A.MerkleProof.verify (proof, root, leaf) in
       B.Assert.is_true "should be equal" (expected = computed))

(* same proof, but the first step claims the wrong side for the leaf *)
let proof_wrong_side =
  [
    (0x11d493116efc9abebcdb30c7df8d41f0f2d80bb73348eb7dd4a2ae99c7a62b2c, true);
    (0x23b9ad2635bab6a413a847cee949c20abd17bff89576a9020d553dde2401d3f3, true)
  ]

let case_wrong_side =
  B.Model.case
    "verify"
    "verify with a sibling on the wrong side should return false"
    (fun (_ : B.Logger.level) -> let expected = False in
       let computed = A.MerkleProof.verify (proof_wrong_side, root, leaf) in
       B.Assert.is_true "should be equal" (expected = computed))

let case_wrong_root =
  B.Model.case
    "verify"
    "verify against another root should return false"
    (fun (_ : B.Logger.level) -> let expected = False in
       let other_root =
         0x4ea4cd9389fa1c4cfd8051d32bd3ee7c898690139a94c32d566f6d55b0ad4447 in
       let computed = A.MerkleProof.verify (proof, other_root, leaf) in
       B.Assert.is_true "should be equal" (expected = computed))

let case_nohappy_path =
  B.Model.case
    "verify"
    "verify with invalid args should return false"
    (fun (_ : B.Logger.level) -> let expected = False in
       let computed =
         A.MerkleProof.verify
           (proof, root, 0x11d493116efc9abebcdb30c7df8d41f0f2d80bb73348eb7dd4a2ae99c7a62b2c) in
       B.Assert.is_true "should be equal" (expected = computed))

let suite =
  B.Model.suite
    "Test suite for the MerkleProof Module"
    [case_happy_path; case_wrong_side; case_wrong_root; case_nohappy_path]
