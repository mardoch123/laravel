import{T as v,j as g,o as n,d as u,b as e,u as t,w as r,F as p,Z as _,e as x,a as o,n as y,f as i,i as c,h}from"./app-Pst8fObY.js";import{A as k}from"./AuthenticationCard-D9dO6hl5.js";import{_ as b}from"./AuthenticationCardLogo-D7poGecP.js";import{_ as q}from"./PrimaryButton-B2TZqOkW.js";/* empty css            */import"./_plugin-vue_export-helper-DlAUqK2U.js";const V=o("div",{class:"mb-4 text-sm text-gray-600 dark:text-gray-400"}," Avant de continuer, pourriez-vous vérifier votre adresse électronique en cliquant sur le lien que nous venons de vous envoyer par courrier électronique ? Si vous n'avez pas reçu l'e-mail, nous vous en enverrons un autre avec plaisir. ",-1),w={key:0,class:"mb-4 font-medium text-sm text-green-600 dark:text-green-400"},z={class:"mt-4 flex items-center justify-between"},F={__name:"VerifyEmail",props:{status:String},setup(d){const l=d,s=v({}),f=()=>{s.post(route("verification.send"))},m=g(()=>l.status==="verification-link-sent");return(a,C)=>(n(),u(p,null,[e(t(_),{title:"Email Verification"}),e(k,null,{logo:r(()=>[e(b)]),default:r(()=>[V,m.value?(n(),u("div",w," Un nouveau lien de vérification a été envoyé à l'adresse électronique que vous avez indiquée dans les paramètres de votre profil. ")):x("",!0),o("form",{onSubmit:h(f,["prevent"])},[o("div",z,[e(q,{class:y({"opacity-25":t(s).processing}),disabled:t(s).processing},{default:r(()=>[i(" Renvoyer l'e-mail de vérification ")]),_:1},8,["class","disabled"]),o("div",null,[e(t(c),{href:a.route("profile.show"),class:"underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"},{default:r(()=>[i(" Modifier le profil")]),_:1},8,["href"]),e(t(c),{href:a.route("logout"),method:"post",as:"button",class:"underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 ms-2"},{default:r(()=>[i(" Quitter ")]),_:1},8,["href"])])])],32)]),_:1})],64))}};export{F as default};