#import "airdrop.mligo" "Contract"
#import "../infra/testdata/airdrop_storage.mligo" "T"
let default_storage =
  Contract.generate_initial_storage
    (0x01, T.token, T.merkle_root, (Big_map.empty : Contract.Storage.claimed))
