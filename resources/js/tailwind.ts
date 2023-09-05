import plugin from "tailwindcss/plugin";
import colors from "tailwindcss/colors";

const laravuewind = plugin(
    function ({}) {
    },
    {
        theme: {
            extend: {
                colors: {
                    primary: colors.indigo,
                    secondary: colors.emerald,
                },
            }
        }
    }
);

export default laravuewind;