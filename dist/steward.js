import r from"fs";var s="./public/vendor/js",a={outputDir:s};function c(e={}){return{name:"vite-plugin-markdoc-content",async buildStart(){let o=Object.assign({},a,e),n="vendor/kiwilan/laravel-steward/resources/js/color-mode.js",p="vendor/kiwilan/laravel-steward/dist/steward.cjs",i=process.cwd();await r.promises.mkdir(o.outputDir??s,{recursive:!0}).catch(console.error),r.copyFile(`${i}/${n}`,`${o.outputDir}/color-mode.js`,t=>{if(t)throw t}),r.copyFile(`${i}/${p}`,`${o.outputDir}/steward.js`,t=>{if(t)throw t})}}}var u=c;export{u as steward};
