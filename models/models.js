module.exports ={
    isAdmin: (req, res, next)=>{
        if(!req.User || !req.User.admin){ // передать!!!
           return  res.redirect("/") // to login page
        }
        next()
    },
    isLogged: (req, res, next)=>{
        if(req.session.isLoggedIn){
            return  res.redirect("/") // to login page
         }
         next()
    },
    isGuest: (req, res, next)=>{
        if(!req.session.isLoggedIn){
            return  res.redirect("back") // to login page
         }
         next()
    },
    renderSection : (req, res, section, types, result)=>{
        res.render("sections", {
            path: '/' + section,
            pageTitle: section,
            section: section,
            book: types,
            result: result
        })
    },
    Error404 : (req, res)=>{
        res.status(404).render('404', {
            pageTitle: 'Page Not Found',
            path : "404"
        })
    }
}


