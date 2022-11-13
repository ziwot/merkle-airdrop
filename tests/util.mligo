#import "ligo-breathalyzer/lib/lib.mligo" "Breath"
#import "../src/contract.mligo" "Airdrop"
#import "@ligo/fa/test/fa2/multi_asset.test.mligo" "FA2_helper"

type originated = Breath.Contract.originated

let get_token_initial_storage (owner, token_id, amount_: address * nat * nat) =
  let ledger = Big_map.literal ([ ((owner, token_id), amount_); ]) in
  let operators  = (Big_map.empty : Airdrop.Token.FA2.Operators.t)  in
  let token_metadata = (Big_map.literal [
    (token_id, ({token_id=token_id;token_info=(Map.empty : (string, bytes) map);} : Airdrop.Token.FA2.TokenMetadata.data));
  ] : Airdrop.Token.FA2.TokenMetadata.t) in
  { ledger; token_metadata; operators; }

let originate_token (level: Breath.Logger.level) (owner: address) (token_id: nat) (amount_: nat) () =
  Breath.Contract.originate
    level
    "token_sc"
    Airdrop.Token.FA2.main
    (get_token_initial_storage(owner, token_id, amount_))
    0tez

let originate_airdrop (level: Breath.Logger.level) (admin: address) (token: Airdrop.Token.t) (about: bytes) (merkle_root: bytes) (claimed: Airdrop.Storage.claimed) () =
  Breath.Contract.originate
    level
    "airdrop_sc"
    Airdrop.main
    (Airdrop.generate_initial_storage(admin, token, about, merkle_root, claimed))
    0tez

let request_claim (contract: (Airdrop.parameter, Airdrop.storage) originated) (p: Airdrop.claim_params) () =
  Breath.Contract.transfert_to contract (Claim p) 0tez

let request_set_token (contract: (Airdrop.parameter, Airdrop.storage) originated) (p: Airdrop.set_token_params) () =
  Breath.Contract.transfert_to contract (Set_token p) 0tez

let expected_airdrop_state
    (contract: (Airdrop.parameter, Airdrop.storage) originated)
    (claimed: Airdrop.Storage.claimed)
    (current_balance: tez) : Breath.Result.result =
  let storage = Breath.Contract.storage_of contract in
  let balance = Breath.Contract.balance_of contract in
  let claimed_expectation = Breath.Assert.is_equal "claimed" storage.claimed claimed in
  let ba_expectation = Breath.Assert.is_equal "balance" balance current_balance in
  Breath.Result.reduce [claimed_expectation; ba_expectation]


