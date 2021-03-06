const Book = require("../database/Product")
const User = require("../database/User")
const Model = require('../models/models.js')

const fs = require('fs')
const path = require('path')
const _ = require('lodash')

// index 
// add book page
// edit book 
// cart 
// single book 
// download logic
// user page
// section 
// subsection



// --- index page -------------------------------------------------------------------------- 
exports.index = (req, res, next) => {
    Book.find({})
    .limit(9)
    .then(result => { 
        res.render("index", {
            path: '',
            pageTitle: "Main",
            books: result,
        })
    })
}

// --- add book -------------------------------------------------------------------------- 
exports.addBook = (req, res, next)=> {
    res.render("addBook", {
        path: '/add_book',
        pageTitle: "Add",
        editing: false,
        hasError: false,
        keepetInput : {}
    })
}

// --- edit book -------------------------------------------------------------------------- 
exports.editBook = (req, res, next) => {
    const edit = req.query.edit
    const bookId = req.params.ID
    if(!edit){
        res.redirect("/")
    }
    Book.findById(bookId)
    .then(book => {
        if(!book){
            res.redirect("/")
        }
        res.render("addBook", {
            path: '/edit_Book',
            pageTitle: "Edit",
            editing: edit,
            hasError: false,
            keepetInput : {
                id: book._id,
                title: book.title,
                author : book.author,
                genre: book.genre,
                series: book.series,
                price : book.price,
                description : book.description
            }
        })
    })
    .catch(err => { 
        const error =  new Error(err)
        error.httpStatusCode = 500
        return next(error)
    })
}


// --- cart page -------------------------------------------------------------------------- 
exports.cart = (req, res, next)=>{

    Book.find({_id: req.session.Cart})
    .then(books=>{ // ошибка
        
        let total = books.reduce((total, book)=>{ return total + book.price }, 0)
        
        res.render("cart", {
            path: '/cart',
            pageTitle: "Cart",
            books: books,
            total: total,
        })
    })
    .catch(err => { 
        const error =  new Error(err)
        error.httpStatusCode = 500
        return next(error)
    })

}


// --- single book page -------------------------------------------------------------------------- 
exports.getBook = (req, res, next) => { 

    const id = req.params.ID  
    const title = req.params.title.toLowerCase()
    let added = ( req.session.Cart ) ? req.session.Cart.filter(itm=> itm == id) : []  //  if this book already in the cart change the "buy" button    

    Book.findOne({_id: id, path: title})
    .then((books) => {
        if(!books){
            return  res.redirect("/")
        }
        if(req.User){                    // if user logged, check if he has this book
            User.findById(req.User._id)
            .then(has =>{                
                const isBought = has.orders.filter(book => book == books._id.toString()) 
                res.render("book", {
                    path: '',
                    pageTitle: books.title,
                    book: books,
                    add : added,
                    isBought : isBought,
                })
            })
            .catch(err=>{
                const error =  new Error(err)
                error.httpStatusCode = 404
                return next(error)
            })
        }else{                           // if user is a guest            
            res.render("book", {
                path: '',
                pageTitle: books.title,
                book: books,
                add : added,
                isBought : false,
            })
        }
    })
    .catch(err=>{
        res.redirect("/404")
    })
}

// --- download logic -------------------------------------------------------------------------- 
exports.download = (req, res, next) => {
    const bookID = req.params.bookID
    const bookName = "book.pdf" // bookID + ".pdf"
    const bookPath = path.join('files/books', bookName)

    User.findById(req.User._id)
    .then(user => { 

        const hasBook = user.orders.filter(book => book == bookID) 
        
        if(!hasBook.length){
            return next(new Error("no Book found"))
        }
        fs.readFile(bookPath, (err, data)=>{
            if(err){
                return next(err)
            }
            res.setHeader("Content-Type", 'application/pdf')
            res.setHeader('Content-Disposition', 'attachment; filename="book"')
            res.send(data)
        })
    })
}



// --- User page -------------------------------------------------------------------------- 
exports.userPage = (req, res, next) =>{
    User.findOne({email: req.User.email})
    .populate('orders')
    .exec((err, orders)=>{
        if(err){ 
            console.log(err);
        }
        res.render("user", {
            path: "/user",
            pageTitle: "User Page",
            books: orders
        })
    })
}

exports.contacts = (req, res, next) =>{
    res.render("contacts", {
        path: "/contacts",
        pageTitle: "Contacts",
    })
}


// --- section page (authors, genres, series) -------------------------------------------------------------------------- 
exports.getSection = (req, res, next) => { 
    const section = req.params.section.toLowerCase()
    switch (section) {
        case "author":
            Book.find({}, {author: 1})
            .distinct('author')
            .then((authors) => { 
                Book.find({author: authors})
                .then((result)=>{
                    Model.renderSection(req, res, section, authors, result) // render section page
                })
            })
            .catch(err => { 
                const error =  new Error("Something went wrong!")
                error.httpStatusCode = 500
                return next(error)
            })  
        break;

        case "genre":

            Book.find({}, {genre: 1})
            .distinct('genre')
            .then((genres) => { 
                Book.find({genre: genres})
                .then((result)=>{
                    Model.renderSection(req, res, section, genres, result) // render section page
                })
            })
            .catch(err => { 
                const error =  new Error(err)
                error.httpStatusCode = 500
                return next(error)
            })  
        break;

        case "series":
            Book.find({}, {series: 1})
            .distinct('series')
            .then((series) => { 
                Book.find({series: series})
                .then((result)=>{
                    Model.renderSection(req, res, section, series, result) // render section page
                })
            })
            .catch(err => { 
                const error =  new Error(err)
                error.httpStatusCode = 500
                return next(error)
            })  
        break;

        default:
            Model.Error404(req, res)
        break;
    }

}


// subsection page (e.g. author => J.K. Rowling)
exports.getSubSection = (req, res, next) => { 

    const section = req.params.section.toLowerCase()
    const subsection = req.params.subsection.toLowerCase()

    Book.find({[section]: [subsection] })
    .then((result) => {
        if(!result.length){
           return res.redirect("/404")
        }
        res.render("subSection", {
            path: '/'+section+'',
            pageTitle: _.capitalize(subsection),
            sectionName: _.startCase(_.toLower(subsection)),
            books: result,
        })
    })
    .catch(err => { 
        const error =  new Error(err)
        error.httpStatusCode = 500
        return next(error)
    })  
}
