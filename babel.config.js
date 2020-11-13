module.exports = (api) => {
  const isProduction = () => process.env.NODE_ENV === 'production'
  api.cache(isProduction)

  const presets = [
    '@babel/preset-env',
    '@babel/preset-typescript',
    [
      '@babel/preset-react',
      {
        runtime: 'automatic',
      },
    ],
  ]

  const plugins = [
    '@babel/plugin-transform-runtime',
    '@babel/plugin-proposal-function-sent',
    '@babel/plugin-proposal-export-namespace-from',
    '@babel/plugin-proposal-numeric-separator',
    '@babel/plugin-proposal-throw-expressions',
    '@babel/plugin-syntax-dynamic-import',
    '@babel/plugin-syntax-import-meta',
    ['@babel/plugin-proposal-class-properties', { loose: false }],
    '@babel/plugin-proposal-json-strings',
    ...(api.env(['development', 'test'])
      ? [
          [
            'babel-plugin-module-resolver',
            {
              alias: {
                '@serlo/editor-helpers': '@serlo/editor-helpers/src',
                '@serlo/edtr-io': '@serlo/edtr-io/src',
                '@serlo/i18n': '@serlo/i18n/src',
                '@serlo/legacy-editor-to-editor':
                  '@serlo/legacy-editor-to-editor/src',
                '@serlo/markdown': '@serlo/markdown/src',
                '@serlo/mathjax': '@serlo/mathjax/src',
              },
            },
          ],
        ]
      : []),
  ]

  return { plugins, presets }
}
