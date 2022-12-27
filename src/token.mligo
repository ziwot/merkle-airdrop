#import "@ligo/fa/lib/fa2/asset/single_asset.mligo" "FA"
#import "./errors.mligo" "Errors"
type token_id = nat

type t = address * token_id

let get_transfer_entrypoint (addr : address)
: FA.transfer contract =
  match (Tezos.get_entrypoint_opt "%transfer" addr
         : FA.transfer contract option)
  with
    None -> failwith Errors.fa_not_found
  | Some c -> c

let transfer
  ((token_addr, token_id), to_, amount : t * address * nat)
: operation =
  let dest = get_transfer_entrypoint (token_addr) in
  let transfer_requests =
    ([({from_ = Tezos.get_self_address ();
        txs =
          ([{to_; token_id; amount}]
           : FA.atomic_trans list)})]
     : FA.transfer) in
  Tezos.transaction transfer_requests 0mutez dest
