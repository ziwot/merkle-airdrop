#import "ligo-breathalyzer/lib/lib.mligo" "Breath"
#import "../src/contract.mligo" "Contract"
#import "./util.mligo" "Util"

let case_happy_path =
  Breath.Model.case
    "claim"
    "An happy path when someone claim"
    (fun (level: Breath.Logger.level) ->
      let (operator, (alice, _, _)) = Breath.Context.init_default () in

      let () = Breath.Logger.log level "Initialize Airdrop" in

      let airdrop = Breath.Context.act_as operator
        (Util.originate_airdrop
          level
          alice.address
          0x01
          0x4ea4cd9389fa1c4cfd8051d32bd3ee7c898690139a94c32d566f6d55b0ad4447
          (Set.empty: address set)
        )
      in

      let () = Breath.Logger.log level "Claim Airdrop" in
      let alice_action_1 = Breath.Context.act_as alice
        (Util.request_claim airdrop ({
          addr = ("tz1bD7TRTApzXqvCmY7w6xhM1uRGMGrTxQod" : address);
          amnt = 32n;
          merkle_proof = [
            0x0ffa00dc1b8a89b698e140416c8d162bd9c599e6f5a7e3ef9ffb30a4c6f1d4b1;
            0x61c41e2300aedaec6ef4e4b50462e2da1fa2177f22530df79068d29b44a79b89
        ]}))
      in

      Breath.Result.reduce [
        alice_action_1
        ; Util.expected_airdrop_state
          airdrop
          (Set.literal[("tz1bD7TRTApzXqvCmY7w6xhM1uRGMGrTxQod" : address)])
          0tez
      ]
    )

let case_nohappy_path =
  Breath.Model.case
    "already claimed"
    "Already claimed when someone claim"
    (fun (level: Breath.Logger.level) ->
      let (operator, (alice, _, _)) = Breath.Context.init_default () in

      let () = Breath.Logger.log level "Initialize Airdrop" in

      let airdrop = Breath.Context.act_as operator
        (Util.originate_airdrop
          level
          alice.address
          0x01
          0x4ea4cd9389fa1c4cfd8051d32bd3ee7c898690139a94c32d566f6d55b0ad4447
          (Set.literal[("tz1bD7TRTApzXqvCmY7w6xhM1uRGMGrTxQod" : address)])
        )
      in

      let () = Breath.Logger.log level "Claim Airdrop" in
      let alice_action_1 = Breath.Context.act_as alice
        (Util.request_claim airdrop {
          addr = ("tz1bD7TRTApzXqvCmY7w6xhM1uRGMGrTxQod" : address);
          amnt = 32n;
          merkle_proof = [
            0x0ffa00dc1b8a89b698e140416c8d162bd9c599e6f5a7e3ef9ffb30a4c6f1d4b1;
            0x61c41e2300aedaec6ef4e4b50462e2da1fa2177f22530df79068d29b44a79b89
        ]})
      in

      Breath.Result.reduce [
        Breath.Expect.fail_with_message "ALREADY_CLAIMED" alice_action_1
        ; Util.expected_airdrop_state
          airdrop
          (Set.literal[("tz1bD7TRTApzXqvCmY7w6xhM1uRGMGrTxQod" : address)])
          0tez
      ]
    )

let suite =
  Breath.Model.suite
    "Suite for [Airdrop]"
    [ case_happy_path; case_nohappy_path ]
