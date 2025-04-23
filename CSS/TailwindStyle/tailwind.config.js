
tailwind.config = {
  mode: 'jit',
  darkMode: 'class',
  theme: {
	extend: {
		
	  colors: {
		primary: '#4F46E5',
		secondary: '#10B981',
		dark: '#1F2937',
		accent: '#F59E0B',
	  },
	  fontFamily: {
		sans: ['Inter', 'sans-serif'],
	  },
	  boxShadow: {
		'soft': '0 8px 32px rgba(0,0,0,0.05)',
		'border': 'inset 0 0 0 1px #E5E7EB'
	  }
	}
  }
}
    
    // Автоматическое применение стилей
    document.addEventListener('DOMContentLoaded', function() {
      const elements = {
		  /* 'body': 'flex justify-center min-h-screen',*/
		  /*'body': 'grid place-items-center min-h-screen',*/
		  /*'body': 'justify-center w-full overflow-x-hidden inset-0 -z-10 bg-gradient-to-tr from-blue-300/50 to-yellow-500/50',*/
		  'body': 'min-h-screen w-full bg-gradient-to-tr from-blue-300/50 to-green-500/50',
		 /*'body': 'flex justify-center w-full p-3 bg-gradient-to-tr from-blue-300/50 to-green-500/50',*/
		'main': 'w-full max-w-full md:max-w-[800px] px-auto bg-white/70 md:px-4 mx-auto rounded-xl shadow-lg',
		/*'main': 'w-full px-[4vw] sm:px-[4vw] md:max-w-[800px] md:px-2 mx:px-2',*/
        'h1': 'flex text-5xl bg-red-200/80 font-bold mb-8 mt-12 rounded-2xl',
        'h2': 'text-4xl font-bold mb-6 font-montserrat',
        'h3': 'text-3xl font-bold mb-4',
        'h4': 'text-2xl font-semibold mb-4 font-sans',
        'p': 'text-lg mb-6 leading-relaxed text-gray-600',
        /*'a': 'text-primary hover:opacity-80 transition',*/
        'button': 'bg-primary text-white px-8 py-4 rounded-2xl hover:bg-primary/90 transition-transform hover:scale-[0.98]',
        'section': 'p-4 bg-yellow-50/80 border-l-4 rounded-xl',
/*        'header': 'bg-white shadow-sm sticky top-0 z-50',*/
        'footer': 'bg-white text-white',
        'input': 'border rounded-xl px-6 py-4 w-full text-lg focus:ring-2 focus:ring-primary',
        'textarea': 'border rounded-xl px-6 py-4 w-full h-40 resize-none',
        'img': 'rounded-3xl shadow-soft',
        'blockquote': 'border-l-4 border-accent pl-8 my-8 text-2xl italic bg-green-50 p-8 rounded-2xl',
 /*     'ul': 'list-disc pl-8 mb-6 space-y-2',
    'ol': 'list-decimal pl-8 mb-6 space-y-2',
    'li': 'mb-2',*/
   /* 'blockquote': 'border-l-4 border-accent pl-6 my-6 italic bg-gray-100 p-4 rounded-r',*/
    'pre': 'bg-gray-100 text-gray-800 p-6 rounded-xl overflow-x-auto my-6',
    'code': 'font-mono bg-gray-100 px-1 py-1 rounded text-md',
    'img': 'rounded-2xl shadow-lg my-6',
    'table': 'w-full border-collapse my-6',
    'th': 'bg-gray-100 text-left py-3 px-4 font-semibold border-b',
    'td': 'py-3 px-4 border-b',
    'hr': 'my-8 border-t border-gray-200'     
      }
   
      Object.entries(elements).forEach(([tag, classes]) => {
        document.querySelectorAll(tag).forEach(el => {
          el.classList.add(...classes.split(' '))
        })
      })
    })
