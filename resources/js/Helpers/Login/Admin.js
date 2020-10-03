import AppStroage from './AdminAppStroage'
class Admin {
    login(data) {
        AppStroage.store(data);
    }
    loggedIn() {
        return AppStroage.logged();
    }
    name() {
        if (this.loggedIn()) {
            if (AppStroage.getAdmin()) {
                return AppStroage.getAdmin()['name'];
            } else {
                return "";
            }
        }
    }
    role() {
        if (this.loggedIn()) {
            if (AppStroage.getAdmin()) {
                return AppStroage.getAdmin()['role'];
            } else {
                return "";
            }
        }
    }
    id() {
        if (this.loggedIn()) {
            if (AppStroage.getAdmin()) {
                return AppStroage.getAdmin()['id'];
            } else {
                return "";
            }
        }
    }
    logout() {
        axios.get("/logout").then(res => {
            AppStroage.clear();
            window.location = laravel.baseurl + '/admin/loginme'
        }).catch(error => console.log(error));
    }
}

export default Admin = new Admin();