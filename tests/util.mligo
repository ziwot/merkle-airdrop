#import "ligo-breathalyzer/lib/lib.mligo" "Breath"
#import "../src/contract.mligo" "Airdrop"

type originated = Breath.Contract.originated

let originate_airdrop (level: Breath.Logger.level) (admin: address) (about: bytes) (merkle_root: bytes) (claimed: Airdrop.Storage.claimed) () =
  Breath.Contract.originate
    level
    "airdrop_sc"
    Airdrop.main
    (Airdrop.generate_initial_storage(admin, about, merkle_root, claimed))
    0tez

let request_claim (contract: (Airdrop.parameter, Airdrop.storage) originated) (p: Airdrop.claim_params) () =
  Breath.Contract.transfert_to contract (Claim p) 0tez

let expected_airdrop_state
    (contract: (Airdrop.parameter, Airdrop.storage) originated)
    (claimed: Airdrop.Storage.claimed)
    (current_balance: tez) : Breath.Result.result =
  let storage = Breath.Contract.storage_of contract in
  let balance = Breath.Contract.balance_of contract in
  let claimed_expectation = Breath.Assert.is_equal "claimed" storage.claimed claimed in
  let ba_expectation = Breath.Assert.is_equal "balance" balance current_balance in
  Breath.Result.reduce [claimed_expectation; ba_expectation]
