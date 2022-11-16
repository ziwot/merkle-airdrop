std = "lua54"
ignore = {
	"11./SLASH_.*", -- Setting an undefined (Slash handler) global variable
	"11./BINDING_.*", -- Setting an undefined (Keybinding header) global variable
	"113/LE_.*", -- Accessing an undefined (Lua ENUM type) global variable
	"113/NUM_LE_.*", -- Accessing an undefined (Lua ENUM type) global variable
	-- "211", -- Unused local variable
	"211/_", -- Unused local variable _
	"212", -- Unused argument
	"213", -- Unused loop variable
	"231", -- Set but never accessed
	-- "311", -- Value assigned to a local variable is unused
	"314", -- Value of a field in a table literal is unused
	-- "42.", -- Shadowing a local variable, an argument, a loop variable.
	-- "43.", -- Shadowing an upvalue, an upvalue argument, an upvalue loop variable.
	-- "542", -- An empty if branch
}
globals = {
	"cli",
	"env",
	"fs",
	"hash",
	"Logger",
	"lz",
	"net",
	"path",
	"proc",
	"os",
	"tar",
	"util",
	"ver",
	"zip",
	"am",
	"hjson",
	"table",
	"string",
	"log_success",
	"log_trace",
	"log_debug",
	"log_info",
	"log_warn",
	"log_error",
	"ami_error",
	"ami_assert",
	"PLUGIN_IN_MEM_CACHE",
	"GLOBAL_LOGGER",
	"EXIT_SETUP_ERROR",
	"EXIT_NOT_INSTALLED",
	"EXIT_NOT_IMPLEMENTED",
	"EXIT_MISSING_API",
	"EXIT_ELEVATION_REQUIRED",
	"EXIT_SETUP_REQUIRED",
	"EXIT_UNSUPPORTED_PLATFORM",
	"EXIT_MISSING_PERMISSION",
	"EXIT_INVALID_ELI_VERSION",
	"EXIT_AMI_UPDATE_REQUIRED",
	"EXIT_INVALID_CONFIGURATION",
	"EXIT_INVALID_AMI_VERSION",
	"EXIT_INVALID_AMI_BASE_INTERFACE",
	"EXIT_APP_INVALID_MODEL",
	"EXIT_APP_DOWNLOAD_ERROR",
	"EXIT_APP_IO_ERROR",
	"EXIT_APP_UN_ERROR",
	"EXIT_APP_CONFIGURE_ERROR",
	"EXIT_APP_START_ERROR",
	"EXIT_APP_STOP_ERROR",
	"EXIT_APP_INFO_ERROR",
	"EXIT_APP_ABOUT_ERROR",
	"EXIT_APP_INTERNAL_ERROR",
	"EXIT_APP_UPDATE_ERROR",
	"EXIT_CLI_SCHEME_MISSING",
	"EXIT_CLI_ACTION_MISSING",
	"EXIT_CLI_ARG_VALIDATION_ERROR",
	"EXIT_CLI_INVALID_VALUE",
	"EXIT_CLI_INVALID_DEFINITION",
	"EXIT_CLI_CMD_UNKNOWN",
	"EXIT_CLI_OPTION_UNKNOWN",
	"EXIT_RM_ERROR",
	"EXIT_RM_DATA_ERROR",
	"EXIT_TPL_READ_ERROR",
	"EXIT_TPL_WRITE_ERROR",
	"EXIT_PLUGIN_DOWNLOAD_ERROR",
	"EXIT_PLUGIN_INVALID_DEFINITION",
	"EXIT_PLUGIN_LOAD_ERROR",
	"EXIT_PLUGIN_EXEC_ERROR",
	"EXIT_PKG_DOWNLOAD_ERROR",
	"EXIT_PKG_INVALID_DEFINITION",
	"EXIT_PKG_INVALID_VERSION",
	"EXIT_PKG_INVALID_TYPE",
	"EXIT_PKG_INTEGRITY_CHECK_ERROR",
	"EXIT_PKG_LOAD_ERROR",
	"EXIT_PKG_LAYER_EXTRACT_ERROR",
	"EXIT_PKG_MODEL_GENERATION_ERROR",
	"EXIT_INVALID_SOURCES_FILE",
	"EXIT_UNKNOWN_ERROR",
}
