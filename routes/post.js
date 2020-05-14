const express = require("express");
const {check, body} = require('express-validator/check')

const post = require("../controllers/posts")
const user = require('../models/models')

const router = express.Router()

router.post("/add_book", 
    [
        body('book_name').trim(),
        body('book_series').trim(),
        body('book_author').trim(),
        body('book_genre').trim(),
        body('book_price', 'invalid value of Price. Enter only numbers').isFloat().trim(),
        body('book_description', 'min length of description is 10 characters').isLength({min: 10}).trim()
        // body('book_img','invalid type of Cover. Use only PNG, JPG and JPEG').isEmpty()

    ], user.isAdmin, post.addBookInfo)

router.post("/edit_book",
    [
        body('book_name').trim(),
        body('book_series').trim(),
        body('book_author').trim(),
        body('book_genre').trim(),
        body('book_price', 'invalid value of Price. Enter only numbers').isFloat().trim(),
        body('book_description', 'min length of description is 10 characters').isLength({min: 10}).trim()

    ], user.isAdmin, post.edit_book)

router.post("/addToCart",  post.addToCart)

router.post("/removeFromCart", post.removeFromCart)

router.post("/check_out", post.check_out)

router.post("/sendMail",
[
    body('name').trim(),
    body('mail')
        .trim()            
        .isEmail()
        .withMessage('Please enter a valid email')
        .normalizeEmail({gmail_remove_dots: false}),
    body('subject').trim(),
    body('phone', 'invalid Phone').isFloat().trim(),
    body('text').trim()

], post.sendMail)

router.post("/search", post.search)



module.exports = router;