#import "ligo-breathalyzer/lib/lib.mligo" "Breath"
#import "merkle_proof.mligo" "Result_suite"

let () =
  Breath.Model.run_suites Trace [
    Result_suite.suite
  ]
