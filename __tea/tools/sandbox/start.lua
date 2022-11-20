local _computed = require("__tea.common.computed")

local _cmd = string.interpolate(
	"${ENGINE} run --rm --name ${NAME} --detach -p ${RPC_PORT}:20000 -e block_time=5" .. " ${IMAGE} ${SCRIPT} start",
	_computed.SANDBOX_VARS
)

ami_assert(os.execute(_cmd), "Failed to start sandbox!")
os.execute("sleep 10")

-- TODO: cleanup this
local _deployer = function(options)
	local _deployCmd = "${ENGINE} exec -it ${NAME} ${TEZOS_CLIENT_PATH}"
		.. " originate contract ${CONTRACT_ID} transferring 0 from ${SOURCE} running "
		.. "'${CONTRACT_CODE}' --init '${INITIAL_STORAGE}' --burn-cap ${BURN_CAP} --force"
	local _checkCmd = "${ENGINE} exec -it ${NAME} ${TEZOS_CLIENT_PATH}" .. " show known contract ${CONTRACT_ID}"

	local _vars = util.merge_tables(_computed.SANDBOX_VARS, {
		TEZOS_CLIENT_PATH = options.path or "octez-client",
		BURN_CAP = options["burn-cap"] or "10",
		CONTRACT_ID = options.ID,
		SOURCE = options.source,
		CONTRACT_CODE = string.trim(fs.read_file(string.interpolate("__tea/tools/sandbox/state/${ID}.tz", options))),
		INITIAL_STORAGE = string.trim(
			fs.read_file(string.interpolate("__tea/tools/sandbox/state/${ID}_storage.tz", options))
		),
	}, true)

	local _ok, _, _code = os.execute(string.interpolate(_deployCmd, _vars))
	if not _ok then
		error("exit code " .. tostring(_code))
	end

	local _result = proc.exec(string.interpolate(_checkCmd, _vars), { stdout = "pipe" })
	if _result.exitcode ~= 0 then
		error("failed to get deployed contract address")
	end
	local _deployFile = string.interpolate("deploy/${DEPLOYMENT_ID}-${ID}.json", options)
	local _contractAddress = string.trim(_result.stdoutStream:read("a"))
	if not fs.safe_write_file(_deployFile, hjson.stringify_to_json({ contractAddress = _contractAddress })) then
		error("failed to save contract address to ")
	end
end

local deployment_id = "sandbox"
local id = "token"
log_info("Deploying ${DEPLOYMENT_ID}-${ID}", { DEPLOYMENT_ID = deployment_id, ID = id })
local _ok, _err = pcall(_deployer, {
	DEPLOYMENT_ID = "sandbox",
	ID = "token",
	source = "alice",
})
ami_assert(_ok, "Failed to deploy - " .. id .. "! (" .. tostring(_err) .. ")")
log_success("${ID} deployed.", { ID = id })
