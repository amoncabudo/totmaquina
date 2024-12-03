import $ from "jquery";

import hola from "./hola.js";
import main from "./main.js";

import {Example, obj} from "./example.ts";

 
$(function() {
    console.log('Hello World');
    hola();
    console.log("Example", obj);

});