# Infra

The infra for dev and test environments.

## Containers
- MySQL 5.7
- [adminer](http://localhost:7000/?server=db&username=root&db=app)
- A [Flextesa Sandbox](https://hub.docker.com/r/oxheadalpha/flextesa) running on port 20000

## What do we need?

- test tokens deployed on the sandbox
- test data in the db, those includes deployed test tokens addresses, test users addresses and sample airdrops

### Test token

The deployed test token code has been put in the [testdata](./testdata) directory.

The code is taken from [contract-catalogue](https://github.com/ligolang/contract-catalogue/blob/main/lib/fa2/asset/multi_asset.mligo)

Storage can be compiled with (from inside the [contracts](../contracts) directory):

```sh
ligo compile expression cameligo --init-file test/util.mligo 'get_token_initial_storage(("tz1VSUr8wwNhLAzempoch5d6hLRiTh8Cjcjb": address), 0n, 300n)'
```
