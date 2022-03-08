import ReactDOM from "react-dom";
import {createTheme, CssBaseline, ThemeProvider} from "@mui/material";
import React, {useEffect, useMemo} from "react";
import {BrowserRouter, Redirect, Route, Switch} from "react-router-dom";
import Home from "./pages/Landing/Home";
import LandingLayout from "./layouts/LandingLayout";
import Seekers from "./pages/Landing/Seekers";
import Profile from "./pages/Landing/Profile";
import {Provider, useDispatch, useSelector} from "react-redux";
import store from "./store";
import Donors from "./pages/Landing/Donors";
import Loader from "./components/shared/Loader";
import Post from "./pages/Landing/Post";
import {fetchMe, fetchNotification} from "./store/actions/authActions";
import DonorProfile from "./pages/Landing/DonorProfile";

const App = () => {
    const {siteLoading} = useSelector(state => state.site)
    const token = localStorage.getItem('token')
    const dispatch = useDispatch()

    const theme = useMemo(() => {
        return createTheme({
            typography: {
                fontFamily: ["Open Sans"].join(","),
                h2: {
                    fontSize: 24,
                    fontWeight: 600,
                },
                h3: {
                    fontSize: 20,
                    fontWeight: 600,
                },
                h4: {
                    fontSize: 18,
                    fontWeight: 600,
                },
                h5: {
                    fontSize: 16,
                    fontWeight: 600,
                },
                h6: {
                    fontSize: 14,
                    fontWeight: 600,
                },
                body1: {
                    fontSize: 14,
                },
                body2: {
                    fontSize: 12,
                },
            },
            palette: {
                primary: {
                    main: "#F9311D",
                },
            },
        });
    }, []);

    useEffect(() => {
        let token = localStorage.getItem('token') || null
        if(token){
            dispatch(fetchMe())
        }



    }, [])

    useEffect(()=>{
        dispatch(fetchNotification())
    },[])


    return (
        <ThemeProvider theme={theme}>
            <CssBaseline/>

            {siteLoading && <Loader/>}

            <BrowserRouter>
                <Switch>
                    <LandingLayout>
                        <Route exact path="/" component={Home}/>
                        <Route exact path="/donors" component={Donors}/>
                        <Route exact path="/seekers" component={Seekers}/>
                        <Route exact path="/post" component={Post}/>
                        <Route exact path="/donor-profile/:id" component={DonorProfile}/>

                        <Route exact path="/profile">
                            {token ? <Profile /> : <Redirect to="/" /> }
                        </Route>

                    </LandingLayout>
                </Switch>
            </BrowserRouter>


        </ThemeProvider>
    );
};

if (document.getElementById("root")) {
    let root = document.getElementById("root");
    ReactDOM.render(
        <Provider store={store}>
            <React.StrictMode>
                <App/>
            </React.StrictMode>
        </Provider>,
        root
    );
}
