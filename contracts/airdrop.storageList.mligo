#include "airdrop.mligo"
#import "../infra/testdata/airdrop_storage.mligo" "T"
let default_storage =
  generate_initial_storage
    (0x01, T.token, T.merkle_root, (Big_map.empty : Storage.claimed))
