import $ from "jquery";
import Sortable from "sortablejs";

import hola from "./hola.js";

import  "./map.js";
import  "./machineinv.js";
import  "./main.js";

import {Example, obj} from "./example.ts";


$(function() {
    console.log('Hello World');
    hola();
    console.log("Example", obj);

});