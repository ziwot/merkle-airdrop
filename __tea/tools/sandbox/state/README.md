# Token for sandbox

Code is taken from [contract-catalogue](https://github.com/ligolang/contract-catalogue/blob/main/lib/fa2/asset/multi_asset.mligo)

Storage can be compiled with:

```sh
ligo compile expression cameligo --init-file tests/util.mligo 'get_token_initial_storage(("tz1VSUr8wwNhLAzempoch5d6hLRiTh8Cjcjb": address), 0n, 300n)' > __tea/tools/sandbox/state/token_storage.tz
```
