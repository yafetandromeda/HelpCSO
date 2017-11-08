// JavaScript Document
function getNow(){
	var now = new Date();
	return {
		label: now.getDate() + " - " + (now.getMonth() + 1) + " - " + now.getFullYear() + " " + now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds(),
		value: now.getFullYear() + " - " + (now.getMonth() + 1) + " - " + now.getDate() + " " + now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds()
		}
	}