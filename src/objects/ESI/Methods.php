<?php

    namespace Ridley\Objects\ESI;

    class Methods extends Base {

        protected function character_affiliations(array $arguments) {

            return $this->makeRequest(
                endpoint: "/characters/affiliation/",
                url: ($this->esiURL . "characters/affiliation/?datasource=tranquility"),
                method: "POST",
                payload: $arguments["characters"],
                cacheTime: 3600,
                retries: (isset($arguments["retries"]) ? $arguments["retries"] : 0)
            );

        }

        protected function authenticated_search(array $arguments) {

            $categories = implode(",", $arguments["categories"]);
            $search = urlencode($arguments["search"]);
            $language = (isset($arguments["language"]) ? ("&language=" . $arguments["language"]) : "");
            $strict = (isset($arguments["strict"]) ? ("&strict=" . $arguments["strict"]) : "");

            $url = $this->esiURL . "characters/" . $arguments["character_id"] . "/search/?datasource=tranquility&categories=" . $categories . "&search=" . $search . $language . $strict;

            return $this->makeRequest(
                endpoint: "/characters/{character_id}/search/",
                url: $url,
                accessToken: $this->accessToken,
                retries: (isset($arguments["retries"]) ? $arguments["retries"] : 0)
            );

        }

        protected function universe_names(array $arguments) {

            return $this->makeRequest(
                endpoint: "/universe/names/",
                url: ($this->esiURL . "universe/names/?datasource=tranquility"),
                method: "POST",
                payload: $arguments["ids"],
                cacheTime: 3600,
                retries: (isset($arguments["retries"]) ? $arguments["retries"] : 0)
            );

        }

        protected function corporation_contracts(array $arguments) {

            $page = $arguments["page"] ?? 1;
            $url = $this->esiURL . "corporations/" . $arguments["corporation_id"] . "/contracts/?page=" . $page . "&datasource=tranquility";

            return $this->makeRequest(
                endpoint: "/characters/{corporation_id}/search/",
                url: $url,
                accessToken: $this->accessToken,
                retries: (isset($arguments["retries"]) ? $arguments["retries"] : 0)
            );

        }

        protected function structures(array $arguments) {

            $url = $this->esiURL . "universe/structures/" . $arguments["structure_id"] . "/?datasource=tranquility";

            return $this->makeRequest(
                endpoint: "/universe/structures/{structure_id}/",
                url: $url,
                accessToken: $this->accessToken,
                cacheTime: 86400,
                retries: (isset($arguments["retries"]) ? $arguments["retries"] : 0)
            );

        }

        protected function stations(array $arguments) {

            $url = $this->esiURL . "universe/stations/" . $arguments["station_id"] . "/?datasource=tranquility";

            return $this->makeRequest(
                endpoint: "/universe/stations/{station_id}/",
                url: $url,
                cacheTime: 86400,
                retries: (isset($arguments["retries"]) ? $arguments["retries"] : 0)
            );

        }

        protected function route(array $arguments) {

            $payload = [
                "preference" => ($arguments["preference"] ?? "Shorter")
            ];

            if (isset($arguments["avoid_systems"])) {
                $payload["avoid_systems"] = $arguments["avoid_systems"];
            }
            if (isset($arguments["connections"])) {
                $payload["connections"] = $arguments["connections"];
            }
            if (isset($arguments["security_penalty"])) {
                $payload["security_penalty"] = $arguments["security_penalty"];
            }

            return $this->makeRequest(
                endpoint: "/route/{origin_system_id}/{destination_system_id}/",
                url: ($this->esiURL . "route/" . $arguments["origin_system_id"] . "/" . $arguments["destination_system_id"]),
                method: "POST",
                payload: $payload,
                cacheTime: 86400,
                retries: (isset($arguments["retries"]) ? $arguments["retries"] : 0)
            );

        }

    }

?>
