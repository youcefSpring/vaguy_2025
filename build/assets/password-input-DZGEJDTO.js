import{r,j as s}from"./app-lUInwgRy.js";import{I as l}from"./input-C7csDmVJ.js";import{c as p}from"./utils-Cx_fVcGQ.js";import{B as c}from"./button-DzDbuX-O.js";import{E as m}from"./eye-D-efa7vg.js";import{c as h}from"./createLucideIcon-BKiTtyiR.js";/**
 * @license lucide-react v0.364.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const w=h("EyeOff",[["path",{d:"M9.88 9.88a3 3 0 1 0 4.24 4.24",key:"1jxqfv"}],["path",{d:"M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68",key:"9wicm4"}],["path",{d:"M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61",key:"1jreej"}],["line",{x1:"2",x2:"22",y1:"2",y2:"22",key:"a6p6uj"}]]),y=r.forwardRef(({className:o,...e},i)=>{const[a,d]=r.useState(!1),t=e.value===""||e.value===void 0||e.disabled;return s.jsxs("div",{className:"relative",children:[s.jsx(l,{type:a?"text":"password",className:p("hide-password-toggle pr-10",o),ref:i,...e}),s.jsxs(c,{type:"button",variant:"ghost",size:"sm",className:"absolute top-0 right-0 h-full px-3 py-2 hover:bg-transparent",onClick:()=>d(n=>!n),disabled:t,children:[a&&!t?s.jsx(m,{className:"w-4 h-4","aria-hidden":"true"}):s.jsx(w,{className:"w-4 h-4","aria-hidden":"true"}),s.jsx("span",{className:"sr-only",children:a?"Hide password":"Show password"})]}),s.jsx("style",{children:`
					.hide-password-toggle::-ms-reveal,
					.hide-password-toggle::-ms-clear {
						visibility: hidden;
						pointer-events: none;
						display: none;
					}
				`})]})});y.displayName="PasswordInput";export{y as P};
