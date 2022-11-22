const qty = document.querySelector('#qty')
const price = document.querySelector('#price')
const error = document.querySelector('#error')
const description = document.querySelector('#description')
const modal = document.querySelector('#modal')
const btnAdd = document.querySelector('#btnAdd')
const umsr = document.querySelector('#umsr')

window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }

btnAdd.addEventListener('click', ()=>{
    modal.style.display = 'block'
})

function validateData(){
    const nonDecimalUmsr = ["pc","set","doz"]
    // console.log(umsr.value)
    if(isNaN(qty.value) === true || isNaN(price.value) === true){
        error.style.color = 'red'
        error.innerHTML = 'Quantity or Price should be a number'
    }
    else if(qty.value < 0 || price.value < 0){
        error.style.color = 'red'
        error.innerHTML = 'Quantity and Price can never be negative'
    }
    
    else if(nonDecimalUmsr.indexOf(umsr.value) !== -1){
        let newqty = qty.value
        if(newqty.indexOf('.') !== -1){
            error.style.color = 'red'
            error.innerHTML = 'Decimal Values are only allowed in kilo'
        }
    }
    else{
    error.style.color = 'green'
    error.innerHTML = 'Item Added Succesfully'
    }
}