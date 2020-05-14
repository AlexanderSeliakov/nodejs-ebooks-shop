const express = require("express");
const get = require("../controllers/gets")
const user = require('../models/models')
const router = express.Router()


router.get("/", get.index)

router.get("/add_book", user.isAdmin, get.addBook)

router.get("/edit_book/:ID", user.isAdmin, get.editBook)

router.get("/cart", get.cart)

router.get("/download/:bookID", user.isGuest,get.download)

router.get("/contacts", get.contacts)

router.get("/user", user.isGuest, get.userPage)

router.get("/book/:title/:ID", get.getBook)

router.get("/:section", get.getSection)

router.get("/:section/:subsection", get.getSubSection)

 // сделать 404

module.exports = router 


