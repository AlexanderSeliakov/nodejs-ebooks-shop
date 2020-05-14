const express = require("express");
const bodyParser = require("body-parser");
const multer = require("multer");
const mongoose = require('mongoose');
const session = require('express-session');     // session 
const flash = require('connect-flash');         // flash notifications 
const helmet = require('helmet')
const compression = require('compression')
const MongodbSession = require('connect-mongodb-session')(session);
 
const User = require("./database/User");

const get = require('./routes/get')
const post = require('./routes/post')
const auth = require('./routes/auth')
const error = require("./routes/errors")

const MONGO_URI = `mongodb+srv://${process.env.MONGO_USER}:${process.env.MONGO_PASSWORD}@cluster-nexcv.azure.mongodb.net/${process.env.MONGO_DEFAULT_DATABASE}`

const app = express();

const storeSession = new MongodbSession({
   uri: MONGO_URI,
   collection: 'sessions',
});

const fileStorage = multer.diskStorage({
   destination: (req, file, cb)=>{
      cb(null, 'img')
   },
    filename: (req, file, cb)=>{
       cb(null, new Date().toISOString() +'-'+ file.originalname)
    }
})

const fileFilters = (req, file, cb)=>{
   if(file.mimetype == 'image/png' || file.mimetype == 'image/jpg' || file.mimetype == 'image/jpeg' ){
      cb(null, true)
   }else{
      cb(null, false)
   }
}

app.set('view engine', 'ejs');
app.set('views', 'views')

app.use(helmet())
app.use(compression())

app.use(bodyParser.urlencoded({extended: true}));
app.use(multer({storage: fileStorage, fileFilter: fileFilters }).single('book_img')); //storage imgs
app.use(express.static("public"));
app.use("/img", express.static("img"));
app.use(session({
   secret: "secret", 
   resave: false, 
   saveUninitialized: false,
   store: storeSession
}));

app.use((req, res, next) => {   // проверить на auth
   if (!req.session.User) {
     return next();
   }
   User.findById(req.session.User._id)
     .then(user => {
        if(!user){
           return next()
        }
       req.User = user;
       next();
     })
     .catch(err => {
        throw new Error(err)
     });
 });

app.use(flash()) // flash

app.use((req, res, next)=>{ // render from each page

   if(req.session.Cart){ // delete books from cart if user already has them
      req.session.Cart =  (req.User) ? req.session.Cart.filter(itm=> !req.User.orders.includes(itm))  :  req.session.Cart
   }
   
   let message = req.flash('message')
   let error = req.flash('error')
   res.locals.isAuthenticated = req.session.isLoggedIn
   res.locals.name =  (req.session.User) ? req.session.User.name : null
   res.locals.isAdmin =  (req.User) ? req.User.admin :  false  // вот тут переделать!!!!!!!!
   res.locals.quantity = (req.session.Cart) ? req.session.Cart.length : 0
   res.locals.messageToUser = (message.length>0) ? message[0] : null
   res.locals.errorMessage = (error.length>0) ? error[0] : null
   res.locals.keepetInput = {}
   // make name session 
   next()
});


app.get('/500', error.get500);
app.use(auth);
app.use(post);
app.use(get);

app.use(error.get404);

app.use((err, req, res, next)=>{
   res.status(500).render('500', {path: '500', pageTitle: " Some Error occured .We're working on this"}); //Some Error occured
})

mongoose.connect(MONGO_URI,
 {useNewUrlParser: true, useUnifiedTopology: true, useFindAndModify: false })
 .then(client=>{
    console.log("db connected");
    app.listen(process.env.PORT || 3000)
 });


// add pagination to main, sections, subsections. cart and user page. EJS with "includes" 

// make CSFR protection (259th video)

// make url make up
