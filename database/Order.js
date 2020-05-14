const mongoose = require('mongoose');

const Schema =  mongoose.Schema

const order = new Schema({
    books: [{
        // type: String,
        // require: true
        type: Schema.Types.ObjectId, 
        ref: 'book'
    }],
    // books :{
    //     type: String,
    //     required: false
    // },
    user : {
        name: {
            type: String,
            required: true
        },
        mail: {
            type: String,
            required: true
        },
        userId: {
            type: Schema.Types.ObjectId,
            required: true,
            ref: 'user'
        }
    }
})


module.exports = mongoose.model("order", order)