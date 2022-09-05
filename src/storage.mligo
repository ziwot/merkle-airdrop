#import "./metadata.mligo" "Metadata"

type t =
  [@layout:comb]
  {metadata : Metadata.t;
   admin : address;
   merkle_root : bytes}
