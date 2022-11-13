#import "./metadata.mligo" "Metadata"
#import "./errors.mligo" "Errors"
#import "./token.mligo" "Token"

type claimed = (address, unit) big_map

type t =
  {metadata : Metadata.t;
   admin : address;
   token: Token.t;
   merkle_root : bytes;
   claimed : claimed}

let assert_not_claimed (s : t) (addr : address) =
  match (Big_map.find_opt addr s.claimed) with
    None -> ()
  | Some -> failwith Errors.already_claimed

let requires_admin (s : t) =
  assert_with_error (Tezos.get_sender() = s.admin) Errors.not_admin

let set_token (s : t) (token : Token.t) =
  let () = requires_admin s in
  {s with token }
