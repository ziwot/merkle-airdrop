#import "ligo-breathalyzer/lib/lib.mligo" "Breath"
#import "../airdrop.mligo" "Airdrop"
type originated = Breath.Contract.originated

let get_token_initial_storage (owner, token_id, amount_ : address * nat * nat) =
  let metadata =
    Big_map.literal
      [("", Bytes.pack ("tezos-storage:contents")); ("contents", ("" : bytes))] in
  let ledger = Big_map.literal ([((owner, token_id), amount_)]) in
  let operators = (Big_map.empty : Airdrop.FA.MultiAsset.operators) in
  let token_metadata =
    (Big_map.literal
       [
         (token_id,
          ({
            token_id = token_id;
            token_info = (Map.empty : (string, bytes) map)
           }
           : Airdrop.FA.TZIP12.tokenMetadataData))
       ]
     : Airdrop.FA.TZIP12.tokenMetadata) in
  {
   metadata;
   ledger;
   token_metadata;
   operators
  }

let originate_token
  (level : Breath.Logger.level)
  (owner : address)
  (token_id : nat)
  (amount_ : nat)
  () =
  Breath.Contract.originate
    level
    "token_sc"
    (contract_of  Airdrop.FA.MultiAsset)
    (get_token_initial_storage (owner, token_id, amount_))
    0mutez

let originate_airdrop
  (level : Breath.Logger.level)
  (about : bytes)
  (token : Airdrop.Token.t)
  (merkle_root : bytes)
  (claimed : Airdrop.Storage.claimed)
  () =
  Breath.Contract.originate
    level
    "airdrop_sc"
    (contract_of  Airdrop)
    (Airdrop.generate_initial_storage (about, token, merkle_root, claimed))
    0mutez

let request_token_transfer
  (contract
   : ( Airdrop.FA.MultiAsset parameter_of, Airdrop.FA.MultiAsset.storage)
       originated)
  (p : Airdrop.FA.TZIP12.transfer)
  () = Breath.Contract.transfer_to contract (Transfer (p)) 0mutez

let request_claim
  (contract : ( Airdrop parameter_of, Airdrop.storage) originated)
  (p :  Airdrop parameter_of)
  () = Breath.Contract.transfer_to contract p 0mutez

let expected_token_state
  (contract
   : ( Airdrop.FA.MultiAsset parameter_of, Airdrop.FA.MultiAsset.storage)
       originated)
  (operators : Airdrop.FA.MultiAsset.operators)
: Breath.Result.result =
  let storage = Breath.Contract.storage_of contract in
  let operators_expectation =
    Breath.Assert.is_equal "operators" storage.operators operators in
  Breath.Result.reduce [operators_expectation]

let expected_airdrop_state
  (contract : ( Airdrop parameter_of, Airdrop.storage) originated)
  (claimed : Airdrop.Storage.claimed)
  (current_balance : tez)
: Breath.Result.result =
  let storage = Breath.Contract.storage_of contract in
  let balance = Breath.Contract.balance_of contract in
  let claimed_expectation =
    Breath.Assert.is_equal "claimed" storage.claimed claimed in
  let ba_expectation = Breath.Assert.is_equal "balance" balance current_balance in
  Breath.Result.reduce [claimed_expectation; ba_expectation]
