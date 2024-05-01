class Section {

    constructor() {

        this.cssSections = [];
        this.jsSections = [];
    }

    /*
     * To get necessary elements
    */
    getMetaElement() {

        return document.getElementById('metaForm');
    }

    getCategoryElement() {

        return document.getElementById('category');
    }

    getCssElements() {

        this.cssSections.push(document.getElementById('linkedCssFiles'));
        this.cssSections.push(document.getElementById('cssFiles'));

        return this.cssSections;
    }

    getJsElements() {

        this.jsSections.push(document.getElementById('jsFiles'));
        this.jsSections.push(document.getElementById('linkedJsFiles'));

        return this.jsSections;
    }

    getWidgetElement() {

        return document.getElementById('widget');
    }

    getCdnElement() {

        return document.getElementById('cdn');
    }

    getSlugElement() {

        return document.getElementById('slug');
    }
}
