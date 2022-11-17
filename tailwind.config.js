const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [        
        './app/**/*.php',
        './resources/**/*.html',
        './resources/**/*.js',
        './resources/**/*.jsx',
        './resources/**/*.ts',
        './resources/**/*.tsx',
        './resources/**/*.php',
        './resources/**/*.vue',
        './resources/**/*.twig',           
    ],
    theme: {
        extend: {
            
            backgroundImage: {
                'home-hero': "url('/images/home/hero.png')",    
                'footer-image-pattern': 'linear-gradient(0deg, rgba(7,0,0,1) 6%, rgba(121,9,9,0) 35%, rgba(255,255,255,1) 100%)',  
                'calendar-selected-pattern' : 'linear-gradient(to right, white 20%, #E11166 20%, #E11166 40%, #E11166 40%, #E11166 60%, #E11166 60%, #E11166 80%, white 80%, white 100%)',              
            },
            boxShadow: {
                't-xl': '1px 1px 54px -30px rgba(0,0,0,0.3),1px 1px 54px -30px rgba(0,0,0,0.3)'
            },
            colors: {               
                'black-primary': '#300000',
                'black-secondary': '#3B3E44', 
                'black-not-deep': '#14272C',
                'gray-primary' : '#F4F5F7',
                'gray-secondary' : '#E7E8EA',
                'gray-tertiary' : '#B1B5C4',
                'pink-red':'#E11166',
                'pink-red-secondary' : '#FDEEF4',
            },
            fontFamily: {
                sans: ['DMSans-Regular', ...defaultTheme.fontFamily.sans],    
                fredokaOne: ['FredokaOne'],            
                fredoka: ['Fredoka'],    
            },
            maxWidth: {
                '6xl': '1170px',
                '7xl': '1440px',
            },
            padding: {
                '22' : "84px",
            } ,
            width: {
                '6xl': '1170px',
            },                                  
        },                       
    },        
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/line-clamp'),
    ],
};
