# Infra

A collection of scripts to generate test data

## bootstrapped.ts

## makeAccounts.ts

## makeDrops.ts

## makeToken.ts

The deployed test token code has been put in the [testdata](./testdata) directory.

The code is taken from [contract-catalogue](https://github.com/ligolang/contract-catalogue/blob/main/lib/fa2/asset/multi_asset.mligo)

Storage can be compiled with (from inside the [contracts](../contracts) directory):

⚠ TODO: fixme (not working any more since last ligo updates)

```sh
ligo compile expression cameligo --init-file tests/util.mligo 'get_token_initial_storage(("tz1VSUr8wwNhLAzempoch5d6hLRiTh8Cjcjb": address), 0n, 300n)'
```

## makeProof.ts
