
export const variables = {
    genders: [
        { name: "No especificado", id: 1 },
        { name: "Femenino", id: 2 },
        { name: "Masculino", id: 3 }
    ],
    proceedingsWithSpouse: [2, 4]
}

export const func = {
    getGender(type) {
        if (type == "1") return "No especificado";
        if (type == "2") return "Femenino";
        if (type == "3") return "Masculino";

        return "No especificado";
    }
}
