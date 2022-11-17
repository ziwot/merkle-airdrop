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

let generate (about, token, merkle_root, claimed :
   bytes * Token.t * bytes * claimed) =
  let metadata = (Big_map.empty : Metadata.t) in
  let metadata : Metadata.t =
    Big_map.update
      ("")
      (Some (Bytes.pack ("tezos-storage:content")))
      metadata in
  let metadata =
    Big_map.update ("content") (Some (about)) metadata in
  let config = { token; merkle_root } in
  {metadata; config; claimed}
