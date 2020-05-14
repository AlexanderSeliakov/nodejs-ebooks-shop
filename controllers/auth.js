const User = require("../database/User")

const crypto = require('crypto')
const bcrypt = require('bcryptjs')
const {validationResult} = require('express-validator/check')
const nodeMailer = require('nodemailer')
const sendGrid = require('nodemailer-sendgrid-transport')

const transporter = nodeMailer.createTransport(sendGrid({
    auth:{
        // api_user: ,
        api_key : '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'
    }
}))

// --- log in -------------------------------------------------------------------------- 

exports.getLogin = (req, res)=>{
    res.render("auth/login", {
        path: '',
        pageTitle: 'Log in, please',
        keepetInput: {}
    })
}



exports.postLogin = (req, res, next) => {
    let email = req.body.email
    let password = req.body.password
    const errors = validationResult(req)
    if(!errors.isEmpty()){
        return res.status(422)
        .render("auth/login", {
            path: '',
            pageTitle: 'Log in, please',
            messageToUser: errors.array()[0].msg,
            keepetInput: {email: email}
        })
    }

    User.findOne({email: email, })
    .then(user=>{
        // if(!user){
        //     return res.status(422).render("auth/login", {
        //         path: '',
        //         pageTitle: 'Log in, please',
        //         messageToUser: "Please enter a valid email",
        //         keepetInput: {email: email}
        //     })
        // }
        bcrypt.compare(password, user.password)
        .then( match =>{
            if(match){
                req.session.User = user
                req.session.isLoggedIn = true;
                req.session.quantity = (req.session.Cart) ? req.session.Cart.length : 0
                return res.redirect("/") // to 
            }
            return res.status(422).render("auth/login", {
                path: '',
                pageTitle: 'Log in, please',
                messageToUser: "Wrong Password",
                keepetInput: {email: email}
            })
        })
        .catch(err => { 
            const error =  new Error(err)
            error.httpStatusCode = 500
            return next(error)
        })
    })
    .catch(err => { 
        const error =  new Error(err)
        error.httpStatusCode = 500
        return next(error)
    })
}


// --- sign up  -------------------------------------------------------------------------- 

exports.getSignup = (req, res)=>{
    res.render("auth/signup", {
        path: '',
        pageTitle: 'Registration',
        keepetInput : {}
    })
}


exports.postSignup = (req, res, next)=>{
    const name = req.body.name
    const email = req.body.email
    const password = req.body.password
    const errors = validationResult(req)

    if(!errors.isEmpty()){
        return res.status(422)
        .render("auth/signup", {
            path: '',
            pageTitle: 'Registration',
            messageToUser: errors.array()[0].msg,
            keepetInput: {name: name, email: email}
        })
    }

    bcrypt.hash(password, 12)
    .then(hashedPsw=>{
        const userData = new User({ 
            name : name,
            email: email,
            password : hashedPsw,
        }) 
        return userData.save()
    })
    .then((itm)=>{      // needs to be async or site will bw slooooow !!!!!!!!!!
        transporter.sendMail({
            to: email,
            from: 'alexander.seliakov@gmail.com',
            subject : 'Subject of letter',
            html : "<h1>success</h1>"
        })
    }).then(r=>{
        res.redirect("/login")
    })
    .catch(err => { 
        const error =  new Error(err)
        error.httpStatusCode = 500
        return next(error)
    })
}


// --- reset password  -------------------------------------------------------------------------- 
exports.getReset = (req, res, next)=>{
    res.render("auth/reset", {
        path: '/reset',
        pageTitle: 'Reset Password'
    })
}


exports.postReset = (req, res, next)=>{
    const email = req.body.email
    const errors = validationResult(req)

    if(!errors.isEmpty()){
        return res.status(422)
        .render("auth/reset", {
            path: '/reset',
            pageTitle: 'Reset Password',
            messageToUser: errors.array()[0].msg,
            keepetInput: {email: email}
        })
    }

    crypto.randomBytes(32, (err, buffer)=>{
        if(err){
            console.log(err);
            return res.redirect('/')
        }
        const token = buffer.toString('hex')        

        User.findOne({email: req.body.email})
        .then(user=>{
            user.resetToken = token
            user.resetTokenExpiratoin = Date.now() + 3600000;            
            return user.save()
        })
        .then((itm)=>{      // needs to be async or site will bw slooooow !!!!!!!!!!
            transporter.sendMail({
                to: req.body.email,
                from: 'alexander.seliakov@gmail.com',
                subject : 'Password Reset',
                html : '<p>Click that <a href="localhost:3000/reset/'+token+'"> Link </a> to reset your password </p> <p> or  copy the link localhost:3000/reset/'+token+'</p>'
            })
        }).then(r=>{
            req.flash('message', 'Please, chek your email!')
            res.redirect("/")
        })
        .catch(err => { 
            const error =  new Error(err)
            console.log(err);
            
            // error.httpStatusCode = 500
            // return next(error)
        })
        
    })
}



// --- new Password -------------------------------------------------------------------------- 

exports.getNewPassword = (req, res, next)=>{
    const token = req.params.token
    User.findOne({resetToken: token, resetTokenExpiratoin: {$gt: Date.now()}})
    .then(user=>{
        res.render("auth/new-pass", {
            path: '/new-pass',
            pageTitle: 'New Password',
            userId: user._id.toString(),
            passwordToken: token
        })
    })
    .catch(err=>{
        console.log(err);
        res.redirect("/")
    })
}

exports.postNewPassword = (req, res, next)=>{
    const newPass = req.body.password
    const newPass2 = req.body.password2
    const userId = req.body.userId
    const passwordToken = req.body.passwordToken
    const errors = validationResult(req)
    let resetUser;

    if(!errors.isEmpty()){
        return res.status(422)
        .render("auth/new-pass", {
            path: '/new-pass',
            pageTitle: 'New Password',
            messageToUser: errors.array()[0].msg,
            userId: userId.toString(),
            passwordToken: passwordToken
        })
    }

    User.findOne({resetToken: passwordToken, resetTokenExpiratoin: {$gt: Date.now()}, _id: userId })
    .then(user=>{
        resetUser = user
        return bcrypt.hash(newPass, 12)
    })
    .then(hashedPsw=>{
        resetUser.password = hashedPsw
        resetUser.token = undefined
        resetUser.resetTokenExpiratoin = undefined
        console.log("password has been successfully changed");
        
        return resetUser.save()
    })
    .then(result=>{
        res.redirect('/login')
    })
    .catch(err => { 
        const error =  new Error(err)
        error.httpStatusCode = 500
        return next(error)
    })    
}

// --- log out  -------------------------------------------------------------------------- 

exports.postLogOut = (req, res)=>{
    req.session.destroy((err)=>{
        console.log("errors: " + err);
        res.redirect("/")
    })
}