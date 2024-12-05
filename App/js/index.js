import $ from "jquery";

import hola from "./hola.js";

import  "./map.js";
import  "./machineinv.js";


import {Example, obj} from "./example.ts";


$(function() {
    console.log('Hello World');
    hola();
    console.log("Example", obj);

});