#import "./config.mligo" "Config"
#import "./metadata.mligo" "Metadata"
#import "./errors.mligo" "Errors"
#import "./token.mligo" "Token"

type claimed = (address, unit) big_map

type t =
  {metadata : Metadata.t;
   config : Config.t;
   claimed : claimed}

let assert_not_claimed (s : t) (addr : address) =
  match (Big_map.find_opt addr s.claimed) with
    None -> ()
  | Some -> failwith Errors.already_claimed

let register_claim (s : t) (addr : address) =
  let claimed = Big_map.add addr unit s.claimed in
  { s with claimed }
