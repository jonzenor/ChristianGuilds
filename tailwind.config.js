module.exports = {
  purge: [
    './resources/views/**/*.blade.php',
    './resources/css/**/*.css',
  ],
  theme: {
    extend: {
      colors: {
        'cggray-500': '#2B404F',
        'cggray-600': '#243542',
        'cggray-700': '#1D2B35',
        'cggray-800': '#162027',
        'cggray-900': '#0E151A',
  
        'cgblack-900': '#07080D',
  
        'cgpink-100': '#EEAABE',
        'cgpink-200': '#E1668B',
        'cgpink-300': '#DA4471',
        'cgpink-400': '#CC285A',
        'cgpink-500': '#B6244F',
        'cgpink-600': '#AA224B',
        'cgpink-700': '#991E43',
        'cgpink-800': '#771834',
        'cgpink-900': '#440D1E',
  
        'cggreen-50': '#A4F4F2',
        'cggreen-100': '#6EEDE9',
        'cggreen-200': '#49E9E3',
        'cggreen-300': '#25E4DE',
        'cggreen-400': '#19C8C2',
        'cggreen-500': '#14A39F',
        'cggreen-600': '#12918D',
        'cggreen-700': '#0D6D6A',
        'cggreen-800': '#094947',
        'cggreen-900': '#042423',

        'cgblue-50': '#D6F1FF',
        'cgblue-100': '#ADE4FF',
        'cgblue-200': '#85D6FF',
        'cgblue-300': '#5CC9FF',
        'cgblue-400': '#33BBFF',
        'cgblue-500': '#00AAFF',
        'cgblue-600': '#0096E0',
        'cgblue-700': '#007AB8',
        'cgblue-800': '#00527A',
        'cgblue-900': '#001B29',

        'cgpurple-50': '#EFDCFA',
        'cgpurple-100': '#E6CAF7',
        'cgpurple-200': '#C683EC',
        'cgpurple-300': '#B55FE7',
        'cgpurple-400': '#A53CE2',
        'cgpurple-500': '#9320D5',
        'cgpurple-600': '#791AB1',
        'cgpurple-700': '#62158E',
        'cgpurple-800': '#49106A',
        'cgpurple-900': '#250835',
  
  
        cggray: '#1D2B35',
        cgblack: '#0F101A',
        cggreen: '#14A39F',
        cgpink: '#B6244F',
        cgwhite: '#E7EFE9',
      }  
    }
  },
  variants: {},
  plugins: [
    require('@tailwindcss/ui'),
  ]
}
