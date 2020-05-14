const express = require("express");
const {check, body} = require('express-validator/check')

const auth = require("../controllers/auth")
const model = require('../models/models')
const User = require('../database/User')

const router = express.Router()

// GET 
router.get("/login", model.isLogged, auth.getLogin)

router.get("/signup",model.isLogged,  auth.getSignup)

router.get("/reset", auth.getReset)

router.get("/reset/:token", auth.getNewPassword)


// POST
router.post("/login", 
    [
        check('email')
            .isEmail()
            .withMessage('Please enter a valid email')
            .normalizeEmail({gmail_remove_dots: false})
            .custom((val, {req})=>{
                return User.findOne({email : val}) //make with "{ unique: true }"
                .then(user=>{
                    if(!user){
                        return Promise.reject('Please enter a valid email')
                    }
                })
            }),


        body('password', 'Please, enter at least 5 characters for password')
            .isLength({min: 5})
            .trim()
    ], auth.postLogin) // login code
    
    
router.post("/signup", 
    [ 
        check('email')
            .isEmail()
            .withMessage('Please enter a valid email')
            .normalizeEmail({gmail_remove_dots: false})
            .custom((val, {req})=>{
                return User.findOne({email : val}) //make with "{ unique: true }"
                .then(user=>{
                    if(user){
                        return Promise.reject('This email already exists')
                    }
                })
            }),

        body('password', 'Please, enter at least 5 characters for password.')
            .isLength({min: 5})
            .trim(),
        
        body('password2')
            .trim()
            .custom((val, {req})=>{
                if(val != req.body.password){
                    throw Error("Passwords don't match")
                }
                return true
            })

    ], auth.postSignup)

router.post("/reset",
        check('email')
            .isEmail()
            .withMessage('Please enter a valid email')
            .normalizeEmail({gmail_remove_dots: false})
            .custom((val, {req})=>{
                return User.findOne({email : val}) //make with "{ unique: true }"
                .then(user=>{
                    if(!user){
                        return Promise.reject('No account fffound')
                    }
                })
            }),
 auth.postReset)

router.post("/new-password", 
    [
        body('password', 'Please, enter at least 5 characters for password.')
        .isLength({min: 5})
        .trim(),

        body('password2')
        .trim()
        .custom((val, {req})=>{
            if(val != req.body.password){
                throw Error("Passwords don't match")
            }
            return true
        })

    ], auth.postNewPassword)

router.post("/logout", auth.postLogOut)

module.exports = router;