#import "./metadata.mligo" "Metadata"

type t =
  {metadata : Metadata.t;
   admin : address;
   merkle_root : bytes;
   claimed : address set}
