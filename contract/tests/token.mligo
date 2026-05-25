#import "@ligo/fa/lib/main.mligo" "FA2"

let get_token_initial_storage
  (about, owner, token_id, amount_ : bytes * address * nat * nat)
: FA2.MultiAsset.storage =
  let metadata =
    Big_map.literal
      [("", [%bytes "tezos-storage:contents"]); ("contents", about)] in
  let ledger = Big_map.literal ([((owner, token_id), amount_)]) in
  let operators = (Big_map.empty : FA2.MultiAsset.operators) in
  let token_metadata =
    (Big_map.literal
       [
         (token_id,
          ({
            token_id = token_id;
            token_info = (Map.empty : (string, bytes) map)
           }))
       ]) in
  {
   ledger;
   token_metadata;
   operators;
   metadata
  }
