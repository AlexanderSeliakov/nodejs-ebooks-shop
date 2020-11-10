const Book = require("../database/Product")
const User = require("../database/User")
const Order = require("../database/Order")
const {validationResult} = require('express-validator/check')
const nodeMailer = require('nodemailer')
const sendGrid = require('nodemailer-sendgrid-transport')
const _ = require('lodash')

const transporter = nodeMailer.createTransport(sendGrid({
    auth:{
        // api_user: ,
        api_key : process.env.KEY
    }
}))


// add book 
// add to cart 
// remove from cart
// chek out
// send email 
// search

// --- add Book -------------------------------------------------------------------------- 
exports.addBookInfo = (req, res) => {
    const id = req.body.bookId
    const title = req.body.book_name  // тут привести в порядок строкм
    const author = req.body.book_author
    const genre = req.body.book_genre
    const series = req.body.book_series
    const price = req.body.book_price
    const description = req.body.book_description
    const img = req.file

    const errors = validationResult(req)

    if(!errors.isEmpty() || !img){
        if(errors.array()){ console.log(errors.array()[0].msg) } 
        
        return res.status(422)
            .render("addBook", {
            path: '/add_book',
            pageTitle: "Add",
            editing: false,
            hasError: true,
            keepetInput : {
                title: title,
                author : author,
                genre: genre,
                series: series,
                price : price,
                description : description
            },
            messageToUser: (!img) ? "invalid type of Cover. Use only PNG, JPG and JPEG" : errors.array()[0].msg 
        })
    }
    const addBook = Book({
        title: title,
        path: _.kebabCase(title),
        author: _.kebabCase(author.toLowerCase()),
        genre: _.kebabCase(genre.toLowerCase()),
        series: _.kebabCase(series.toLowerCase()),
        price: price,
        description: description,
        cover: img.path
    })

    addBook.save()
    .then(cb => {
        req.flash("message", "Book Added")
        console.log("Book Added");
        res.redirect("back")
    })
    .catch(err => {
        const error =  new Error(err)
        error.httpStatusCode = 500
        return next(error)
    })
}



// --- edit book -------------------------------------------------------------------------- 

exports.edit_book = (req, res, next) => {
    const id = req.body.bookID
    const title = req.body.book_name
    const author = req.body.book_author
    const genre = req.body.book_genre
    const series = req.body.book_series
    const price = req.body.book_price
    const description = req.body.book_description
    const img = req.file

    const errors = validationResult(req)

    if(!errors.isEmpty()){
        console.log(errors.array()[0].msg);
        
        return res.status(422)
            .render("addBook", {
            path: '/edit_Book',
            pageTitle: "Edit",
            editing: true,
            hasError: true,
            keepetInput : {
                id: id,
                title: title,
                author : author,
                genre: genre,
                series: series,
                price : price,
                description : description
            },
            messageToUser: errors.array()[0].msg,
        })
    }
    
    Book.findById(id)
    .then(updatedBook=>{
        updatedBook.title = title,
        updatedBook.path = _.kebabCase(title),
        updatedBook.author = _.kebabCase(author.toLowerCase()),
        updatedBook.genre = _.kebabCase(genre.toLowerCase()),
        updatedBook.series = _.kebabCase(series.toLowerCase()),
        updatedBook.price = price,
        updatedBook.description = description
        if(img){
            updatedBook.cover = img.path
        }
        return updatedBook.save()
        .then(cb => {
            console.log("Book Changed!");
            res.redirect("/book/"+ title + "/" + id)
        })
    })
    .catch(err => { 
        const error =  new Error(err)
        error.httpStatusCode = 500
        return next(error)
    })
}


// --- add To Cart -------------------------------------------------------------------------- 
exports.addToCart = (req, res, next) => {

    let bookId = req.body.addToCart
    if (req.session.Cart) {
        if (!req.session.Cart.includes(bookId)) {
            req.session.Cart.push(bookId)
        }
    } else {
        req.session.Cart = [bookId]
    }

    res.redirect('back');
}


// --- remove From Cart -------------------------------------------------------------------------- 
exports.removeFromCart = (req, res, next) => {
    let bookId = req.body.removeFromCart;
    req.session.Cart = req.session.Cart.filter(itm => itm != bookId);
    res.redirect('back');
}

// --- check out -------------------------------------------------------------------------- 
exports.check_out = (req, res, next) => {
    const total = req.body.check_out
    if (req.User) {
        User.findById(req.User._id)
        .then((user) => {
            const order = Order({
                user: {
                    name: req.User.name,
                    mail: req.User.email,
                    userId: req.User._id
                },
                books: req.session.Cart
            })
            user.orders.push(...req.session.Cart)
            user.save()
            order.save()
        })
        .then(f=>{
            req.flash("message", "The book has been added to your catalogue")            
            console.log("order Added");
            req.session.Cart = []
            res.redirect("/user")
        })
        .catch(err => { 
            const error =  new Error(err)
            error.httpStatusCode = 500
            return next(error)
        })

    } else {
        return res.redirect("/login")
    }
}


// --- send mail from customers -------------------------------------------------------------------------- 

exports.sendMail = (req, res, next)=>{
    const name = req.body.name
    const mail = req.body.mail
    const subject = req.body.subject
    const phone = req.body.phone || 0
    const text = req.body.text

    transporter.sendMail({
        to: 'alexander.seliakov@gmail.com',
        from: 'alexander.seliakov@gmail.com',
        subject : 'Subject of letter',
        html : "<h3>subject: "+ subject +"</h3>" + 
            "<h4>from: "+ name +"</h4>" + 
            "<h4>mail: "+ mail +"</h4>" + 
            "<h4>phone: "+ phone +"</h4>" + 
            "<p>message: "+ text +"</p>"
    })
    .then(r=>{
        req.flash("message","Message was successfully sent")
        res.redirect("back")
    })
    .catch(err => { 
        req.flash("error","Something went wrong")
        res.redirect("back")
    })

}

// --- search -------------------------------------------------------------------------- 

exports.search = (req, res, next)=>{
    const search_value =  _.kebabCase(req.body.search)
    Book.find({path: { $regex: '.*' + search_value + '.*' }})
    .then(result=>{
        res.render("subSection", {   // render subSection page beacuse structure is the same
            path: '/search',
            pageTitle: "Books Found " + result.length,
            sectionName: "What we have",
            books: result,
        })
    })
    .catch(err=>{
        const error =  new Error(err)
        error.httpStatusCode = 500
        return next(error)
        
    })
}