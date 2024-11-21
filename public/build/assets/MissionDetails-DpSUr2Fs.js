import{Q as U,r as i,j as N,x as H,y as O,o as a,d as n,b as z,u as W,w as A,F as g,Z as R,a as e,f as b,t as l,g as k,e as f,h as B,z as $,n as Q,p as J,q as X,S as m}from"./app-Pst8fObY.js";import{_ as Z}from"./AppLayout-DjVfPd7G.js";import{_ as G}from"./_plugin-vue_export-helper-DlAUqK2U.js";/* empty css            */const r=h=>(J("data-v-73a6efe3"),h=h(),X(),h),K={class:"container mx-auto p-6 bg-gray-100 rounded-lg shadow-md overflow-x-auto"},Y=r(()=>e("h2",{class:"text-3xl font-extrabold text-gray-800 mb-6"},"Détails de la Mission",-1)),ee={class:"bg-white p-6 rounded-lg shadow-sm mb-6"},te=r(()=>e("h3",{class:"text-2xl font-semibold text-gray-700 mb-4"},"Informations de la Mission",-1)),se={class:"text-gray-600 mb-2"},oe=r(()=>e("strong",null,"ID:",-1)),re={class:"text-gray-600 mb-2"},le=r(()=>e("strong",null,"Description:",-1)),ae={class:"text-gray-600"},ne=r(()=>e("strong",null,"Adresse:",-1)),ie={class:"bg-white p-6 rounded-lg shadow-sm mb-6 overflow-x-auto"},de=r(()=>e("h3",{class:"text-2xl font-semibold text-gray-700 mb-4"},"Climatiseurs",-1)),ce={class:"min-w-full bg-white border border-gray-300"},ue=r(()=>e("thead",{class:"bg-gray-200"},[e("tr",null,[e("th",{class:"py-3 px-4 border-b text-left"},"Marque"),e("th",{class:"py-3 px-4 border-b text-left"},"Puissance"),e("th",{class:"py-3 px-4 border-b text-left"},"Prix Unitaire"),e("th",{class:"py-3 px-4 border-b text-left"},"Quantité"),e("th",{class:"py-3 px-4 border-b text-left"},"Flexible (m)"),e("th",{class:"py-3 px-4 border-b text-left"},"Nbre étage"),e("th",{class:"py-3 px-4 border-b text-left"},"Wifi")])],-1)),me={class:"py-2 px-4 border-b text-gray-700"},pe={class:"py-2 px-4 border-b text-gray-700"},he={class:"py-2 px-4 border-b text-gray-700"},_e={class:"py-2 px-4 border-b text-gray-700"},ge={class:"py-2 px-4 border-b text-gray-700"},be={class:"py-2 px-4 border-b text-gray-700"},fe={class:"py-2 px-4 border-b text-gray-700"},xe={class:"bg-white p-6 rounded-lg shadow-sm mb-6"},ve=r(()=>e("h3",{class:"text-2xl font-semibold text-gray-700 mb-4"},"Images",-1)),ye={class:"grid grid-cols-2 md:grid-cols-4 gap-4"},we=["src","onClick"],ke=["href"],Me={key:0,class:"fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"},Ee={class:"bg-white p-6 rounded-lg shadow-lg w-full max-w-md md:max-w-lg lg:max-w-xl h-full md:h-auto md:overflow-visible overflow-y-auto"},Se=r(()=>e("h3",{class:"text-xl font-semibold mb-4"},"Télécharger les Photos",-1)),qe={key:0,class:"flex items-center justify-center my-4"},De=r(()=>e("div",{class:"loader"},null,-1)),ze=[De],Ce={class:"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"},Le=["for"],Pe=r(()=>e("span",{class:"text-red-500"},"*",-1)),Ie={class:"flex items-center gap-2"},je=["id","name","onChange"],Te={key:0,class:"w-16 h-16 border border-gray-300 rounded-lg overflow-hidden"},Ve=["src"],Fe=r(()=>e("small",{class:"text-gray-500"},"Taille maximale : 3 Mo",-1)),Ue={key:0,class:"w-full bg-gray-200 rounded-full h-4"},Ne=["disabled"],He={__name:"MissionDetails",setup(h){const{props:x}=U(),_=i(x.mission),C=i(x.climatiseurs),L=i(x.images),v=i(!1),M=i(window.innerWidth),E=N(()=>M.value<=768),S=()=>{M.value=window.innerWidth};H(()=>{window.addEventListener("resize",S)}),O(()=>{window.removeEventListener("resize",S)});const p=i({photo_emplacement_evaporateur:null,photo_numero_serie_evaporateur:null,photo_raccordement_electrique:null,photo_emplacement_condensateur:null,photo_numero_serie_condensateur:null}),y=i(0),u=i(!1),d=i(!0),q=document.querySelector('meta[name="csrf-token"]'),w=q?q.getAttribute("content"):null;w||console.error("Erreur : le jeton CSRF est manquant dans la balise <meta>. Vérifiez que la balise <meta name='csrf-token'> est présente dans votre HTML.");const P=()=>{v.value=!0,d.value=!0},D=()=>{v.value=!1},I=(o,c)=>{const t=o.target.files[0];t&&t.size>3*1024*1024?(m.fire({title:"Erreur",text:"La taille du fichier dépasse 3 Mo. Veuillez sélectionner une image plus petite.",icon:"error"}),o.target.value=""):(p.value[c]=t,j())},j=()=>{d.value=!Object.values(p.value).every(o=>o!==null)},T=o=>o?URL.createObjectURL(o):"",V=async o=>{if(o.preventDefault(),u.value){m.fire({title:"Veuillez patienter",text:"Le chargement des images est en cours. Veuillez patienter jusqu'à la fin.",icon:"info"});return}const c=new FormData;c.append("mission_id",_.value.id),w&&c.append("_token",w);for(let[t,s]of Object.entries(p.value))s&&c.append(t,s);u.value=!0,d.value=!0,y.value=0;try{const t=new XMLHttpRequest;t.open("POST","/poseterminer",!0),t.upload.addEventListener("progress",s=>{s.lengthComputable&&(y.value=Math.round(s.loaded/s.total*100))}),t.onload=async()=>{if(u.value=!1,d.value=!1,t.status===200)m.fire({title:"Upload terminé",text:"Les images ont été téléchargées avec succès",icon:"success"}).then(()=>{window.history.back()}),D();else{const s=JSON.parse(t.responseText);console.error("Erreur lors du téléchargement des images:",s),m.fire({title:"Erreur",text:"Erreur lors du téléchargement des images",icon:"error"})}},t.onerror=()=>{u.value=!1,d.value=!1,m.fire({title:"Erreur",text:"Erreur lors du téléchargement des images",icon:"error"})},t.send(c)}catch(t){console.error("Erreur lors de l'envoi des données:",t),u.value=!1,d.value=!1,m.fire({title:"Erreur",text:"Erreur lors du téléchargement des images",icon:"error"})}};return(o,c)=>(a(),n(g,null,[z(W(R),{title:"Détails de la Mission"}),z(Z,{title:"Détails de la Mission"},{default:A(()=>[e("div",K,[Y,e("div",ee,[te,e("p",se,[oe,b(" "+l(_.value.id),1)]),e("p",re,[le,b(" "+l(_.value.observations),1)]),e("p",ae,[ne,b(" "+l(_.value.adresse),1)])]),e("div",ie,[de,e("table",ce,[ue,e("tbody",null,[(a(!0),n(g,null,k(C.value,t=>(a(),n("tr",{key:t.id},[e("td",me,l(t.marque),1),e("td",pe,l(t.puissance),1),e("td",he,l(t.prix_unitaire),1),e("td",_e,l(t.quantite),1),e("td",ge,l(t.flexible),1),e("td",be,l(t.nbreetage),1),e("td",fe,l(t.wifi),1)]))),128))])])]),e("div",xe,[ve,e("div",ye,[(a(!0),n(g,null,k(L.value,t=>(a(),n("div",{key:t.id,class:"relative group"},[e("img",{src:t.image_path,alt:"Image",class:"w-full h-auto rounded-lg transition-transform transform group-hover:scale-105 cursor-pointer",onClick:s=>o.window.location.href=`/image/${t.id}`},null,8,we),e("a",{href:t.image_path,class:"absolute inset-0",target:"blank"},null,8,ke)]))),128))])]),e("button",{onClick:P,class:"bg-green-500 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-green-600 transition duration-200"}," Marquer comme terminé ")]),v.value?(a(),n("div",Me,[e("div",Ee,[Se,o.showLoader?(a(),n("div",qe,ze)):f("",!0),e("form",{onSubmit:B(V,["prevent"]),class:"flex flex-col space-y-4"},[e("div",Ce,[(a(),n(g,null,k({photo_emplacement_evaporateur:"Photo emplacement évaporateur",photo_numero_serie_evaporateur:"Photo numéro de série évaporateur",photo_raccordement_electrique:"Photo raccordement électrique",photo_emplacement_condensateur:"Photo emplacement condensateur",photo_numero_serie_condensateur:"Photo numéro de série condensateur"},(t,s)=>e("div",{key:s,class:"mb-4"},[e("label",{for:s,class:"block text-gray-700 mb-2 text-sm font-semibold"},[b(l(t)+" ",1),Pe],8,Le),e("div",Ie,[e("input",{type:"file",id:s,name:s,required:"",accept:"image/*",onChange:F=>I(F,s),class:"block w-full text-sm text-gray-600 border border-gray-300 rounded p-2"},null,40,je),p.value[s]?(a(),n("div",Te,[e("img",{src:T(p.value[s]),alt:"Preview",class:"w-full h-full object-cover"},null,8,Ve)])):f("",!0)]),Fe])),64))]),u.value?(a(),n("div",Ue,[e("div",{style:$({width:y.value+"%"}),class:"bg-blue-500 h-4 rounded-full transition-all duration-500"},null,4)])):f("",!0),e("div",{class:Q([{"fixed bottom-0 left-0 w-full bg-white p-4 border-t border-gray-300":E.value,"mt-4 md:relative":!E.value},"z-50 flex flex-col sm:flex-row sm:space-x-4 lg:space-x-6"])},[e("button",{type:"submit",disabled:d.value,class:"w-full bg-blue-500 text-white px-4 py-2 rounded-lg text-lg font-semibold hover:bg-blue-600 transition duration-200 disabled:opacity-50"}," Enregistrer ",8,Ne),e("button",{type:"button",onClick:D,class:"w-full bg-red-500 text-white px-4 py-2 rounded-lg text-lg font-semibold hover:bg-red-600 transition duration-200 mt-4 sm:mt-0"}," Annuler ")],2)],32)])])):f("",!0)]),_:1})],64))}},Be=G(He,[["__scopeId","data-v-73a6efe3"]]);export{Be as default};
