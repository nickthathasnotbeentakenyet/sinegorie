'use strict' 
 
 // Get a list of poems in inventory based on the classificationId 
 let classificationList = document.querySelector("#classificationList"); 
 classificationList.addEventListener("change", function () { 
  let classificationId = classificationList.value; 
  console.log(`classificationId is: ${classificationId}`); 
  let classIdURL = "/sinegorie/poetry/index.php?action=getPoemsList&classificationId=" + classificationId; 
  fetch(classIdURL) 
  .then(function (response) { 
   if (response.ok) { 
    return response.json(); 
   } 
   throw Error("Возникли проблемы с соединением."); 
  }) 
  .then(function (data) { 
   console.log(data); 
   buildPoemsList(data); 
  }) 
  .catch(function (error) { 
   console.log('Ошибка: ', error.message) 
  }) 
 })


 // Build inventory items into HTML table components and inject into DOM 
function buildPoemsList(data) { 
    let poemsDisplay = document.getElementById("poemsDisplay"); 
    // Set up the table labels 
    let dataTable = '<thead>'; 
    dataTable += '<tr><th class="tabPoem">Название произведения</th><td>&nbsp;</td><td>&nbsp;</td></tr>'; 
    dataTable += '</thead>'; 
    // Set up the table body 
    dataTable += '<tbody class="tabgr">'; 
    // Iterate over all poems in the array and put each in a row 
    data.forEach(function (element) { 
     console.log(element.poemId + ", " + element.poemName); 
     dataTable += `<tr><td class="tabPoemN"><a href='/sinegorie/poetry?action=poem-view&poemId=${element.poemId}'>\"${element.poemName}\"</a></td>`; 
     dataTable += `<td class=\"tabPoemU\"><a href='/sinegorie/poetry?action=updateEvent&poemId=${element.poemId}' title='Нажмите для изменения'>Изменить</a></td>`; 
     dataTable += `<td class=\"tabPoemD\"><a href='/sinegorie/poetry?action=deleteEvent&poemId=${element.poemId}' title='Нажмите для удаления'>Удалить</a></td></tr>`; 
    }) 
    dataTable += '</tbody>'; 
    // Display the contents in the Poems Management view 
    poemsDisplay.innerHTML = dataTable; 
   }