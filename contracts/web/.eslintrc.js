module.exports = {
  extends: [
    "eslint:recommended",
    "plugin:@typescript-eslint/recommended",
    "plugin:prettier/recommended",
  ],
  plugins: ["@typescript-eslint"],
  overrides: [
    {
      files: ["**/*.{mj,t}s"],
    },
  ],
};
